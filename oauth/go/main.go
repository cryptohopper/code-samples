// Cryptohopper OAuth2 sample (Go) — three-leg authorization-code flow.
//
// Run a tiny HTTP server that:
//   1. Redirects to cryptohopper.com/oauth2/authorize
//   2. Receives the callback with the auth code
//   3. Exchanges it for an access token at cryptohopper.com/oauth2/token
//   4. Calls api.cryptohopper.com/v1/hopper to demonstrate the token works
//
// Usage:
//   export CRYPTOHOPPER_CLIENT_ID=...
//   export CRYPTOHOPPER_CLIENT_SECRET=...
//   go run main.go
//   open http://localhost:3000/auth

package main

import (
	"crypto/rand"
	"encoding/hex"
	"encoding/json"
	"fmt"
	"io"
	"log"
	"net/http"
	"net/url"
	"os"
	"strings"
	"sync"
)

const (
	cryptohopperHost = "https://www.cryptohopper.com"
	apiHost          = "https://api.cryptohopper.com"
	port             = "3000"
)

var (
	clientID     = os.Getenv("CRYPTOHOPPER_CLIENT_ID")
	clientSecret = os.Getenv("CRYPTOHOPPER_CLIENT_SECRET")
	redirectURI  = "http://localhost:" + port + "/callback"

	mu          sync.Mutex
	accessToken string
	expectedState string
)

func main() {
	if clientID == "" || clientSecret == "" {
		log.Fatal("Set CRYPTOHOPPER_CLIENT_ID and CRYPTOHOPPER_CLIENT_SECRET")
	}

	http.HandleFunc("/", handleRoot)
	http.HandleFunc("/auth", handleAuth)
	http.HandleFunc("/callback", handleCallback)

	log.Printf("Server started at http://localhost:%s", port)
	log.Printf("Open http://localhost:%s/auth to begin the OAuth flow", port)
	log.Fatal(http.ListenAndServe(":"+port, nil))
}

func handleRoot(w http.ResponseWriter, r *http.Request) {
	mu.Lock()
	token := accessToken
	mu.Unlock()

	if token == "" {
		fmt.Fprintf(w, `Not logged in. <a href="/auth">Sign in</a>`)
		return
	}

	req, _ := http.NewRequest("GET", apiHost+"/v1/hopper", nil)
	// Cryptohopper Public API v1: `access-token` header (NOT Authorization: Bearer).
	// See https://www.cryptohopper.com/api-documentation/how-the-api-works
	req.Header.Set("access-token", token)
	resp, err := http.DefaultClient.Do(req)
	if err != nil {
		http.Error(w, "API call failed: "+err.Error(), http.StatusBadGateway)
		return
	}
	defer resp.Body.Close()
	body, _ := io.ReadAll(resp.Body)

	w.Header().Set("Content-Type", "application/json")
	w.Write(body)
}

func handleAuth(w http.ResponseWriter, r *http.Request) {
	state := randomState()
	mu.Lock()
	expectedState = state
	mu.Unlock()

	q := url.Values{}
	q.Set("client_id", clientID)
	q.Set("redirect_uri", redirectURI)
	q.Set("response_type", "code")
	q.Set("scope", "read")
	q.Set("state", state)

	http.Redirect(w, r, cryptohopperHost+"/oauth2/authorize?"+q.Encode(), http.StatusFound)
}

func handleCallback(w http.ResponseWriter, r *http.Request) {
	code := r.URL.Query().Get("code")
	if code == "" {
		http.Error(w, "missing code", http.StatusBadRequest)
		return
	}

	state := r.URL.Query().Get("state")
	mu.Lock()
	want := expectedState
	mu.Unlock()
	if state == "" || state != want {
		http.Error(w, "state mismatch (CSRF protection)", http.StatusBadRequest)
		return
	}

	form := url.Values{}
	form.Set("grant_type", "authorization_code")
	form.Set("client_id", clientID)
	form.Set("client_secret", clientSecret)
	form.Set("redirect_uri", redirectURI)
	form.Set("code", code)

	resp, err := http.Post(
		cryptohopperHost+"/oauth2/token",
		"application/x-www-form-urlencoded",
		strings.NewReader(form.Encode()),
	)
	if err != nil {
		http.Error(w, "token exchange transport error: "+err.Error(), http.StatusBadGateway)
		return
	}
	defer resp.Body.Close()
	body, _ := io.ReadAll(resp.Body)

	if resp.StatusCode != http.StatusOK {
		http.Error(w, fmt.Sprintf("token exchange failed (%d): %s", resp.StatusCode, body), http.StatusBadGateway)
		return
	}

	var tok struct {
		AccessToken string `json:"access_token"`
	}
	if err := json.Unmarshal(body, &tok); err != nil {
		http.Error(w, "token exchange parse error: "+err.Error(), http.StatusBadGateway)
		return
	}

	mu.Lock()
	accessToken = tok.AccessToken
	mu.Unlock()

	http.Redirect(w, r, "/", http.StatusFound)
}

func randomState() string {
	var b [16]byte
	_, _ = rand.Read(b[:])
	return hex.EncodeToString(b[:])
}
