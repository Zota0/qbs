package main

import (
	"fmt"
	"net/http"
)

func noCacheMiddleware(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		w.Header().Set("Cache-Control", "no-cache, no-store, must-revalidate")
		w.Header().Set("Pragma", "no-cache") // For compatibility with older browsers
		w.Header().Set("Expires", "0")
		next.ServeHTTP(w, r)
	})
}

func main() {
	fmt.Println("Starting server on port 8192")

	// Define the root directory for serving files
	rootDir := http.Dir("src")

	// Create an HTTP handler using FileServer
	handler := noCacheMiddleware(http.FileServer(rootDir))

	http.ListenAndServe(":8192", handler)
}
