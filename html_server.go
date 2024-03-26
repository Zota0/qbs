package main

import (
	"fmt"
	"net/http"
)

func main() {
	fmt.Println("Starting server on port 8192")

	// Define the root directory for serving files
	rootDir := http.Dir("src")

	// Create an HTTP handler using FileServer
	handler := http.FileServer(rootDir)

	http.ListenAndServe(":8192", handler)
}
