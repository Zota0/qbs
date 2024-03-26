package main

import (
	"bytes"
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"os"
	"sync"
)

var serverRunning sync.Mutex
var server *http.Server

func handler(w http.ResponseWriter, r *http.Request) {
	defer r.Body.Close()

	body, err := io.ReadAll(r.Body)
	if err != nil {
		fmt.Fprintf(w, "ERROR: reading request body: %v", err)
		return
	}

	var data []bool
	err = json.Unmarshal(body, &data)
	if err != nil {
		fmt.Fprintf(w, "ERROR: unmarshaling JSON data: %v", err)
		return
	}

	if len(data) != 3 {
		fmt.Fprintf(w, "ERROR: invalid data format, expected 3 booleans")
		return
	}

	var buffer bytes.Buffer
	fmt.Fprintf(&buffer, "{\n\t\"publika\": \"%t\",\n\t\"polowa\": \"%t\",\n\t\"telefon\": \"%t\"\n}\n", data[0], data[1], data[2])
	jsonString := buffer.String()

	err = WriteFile(jsonString)
	if err != nil {
		fmt.Fprintf(w, "ERROR: writing data to file: %v", err)
		return
	}

	fmt.Fprintf(w, "Data received and saved successfully!")
}

func WriteFile(data string) error {
	return os.WriteFile("kola_ratunkowe.json", []byte(data), 0644)
}

func startServer(serverStatus *bool) { // Pass server status pointer
	server := &http.Server{Addr: ":7964"} // Initialize server with port
	http.HandleFunc("/", handler)         // Register handler once
	fmt.Println("Server listening on port:", server.Addr)
	serverRunning.Lock() // Acquire lock before updating server status
	defer serverRunning.Unlock()
	// Start the server
	go func() {
		if err := server.ListenAndServe(); err != http.ErrServerClosed {
			fmt.Println("Error starting server:", err)
		}
	}()
	*serverStatus = false // Update server status using pointer
}

func main() {
	isServerRunning := false // Flag to track server status in main

	for true {
		fmt.Println("Option: ")
		var command string
		fmt.Scanln(&command)

		switch command {
		case "listen":
			serverRunning.Lock() // Acquire lock for thread-safe access
			defer serverRunning.Unlock()

			if !isServerRunning {
				go startServer(&isServerRunning) // Start server with pointer to shared variable
				isServerRunning = true           // Update server status in main
			} else {
				fmt.Println("Server already running!")
			}

		case "reset":
			serverRunning.Lock() // Acquire lock for thread-safe access
			defer serverRunning.Unlock()

			if isServerRunning {
				// Access default http instance
				defaultServer := http.DefaultServer
				err := defaultServer.Shutdown(nil) // Gracefully shut down server
				if err != nil {
					fmt.Println("Error stopping server:", err)
				}
				fmt.Println("Server stopped and data reset!")
			} else {
				fmt.Println("Server not running, cannot reset data!")
			}

			var buffer bytes.Buffer
			fmt.Fprintf(&buffer, "{\n\t\"publika\": \"%t\",\n\t\"polowa\": \"%t\",\n\t\"telefon\": \"%t\"\n}\n", true, true, true)
			ResetJSONString := buffer.String()
			WriteFile(ResetJSONString)

		case "exit":
			serverRunning.Lock() // Acquire lock for thread-safe access
			defer serverRunning.Unlock()

			if isServerRunning {
				// Access default http instance
				defaultServer := http.server
				err := defaultServer.Shutdown(nil) // Gracefully shut down server
				if err != nil {
					fmt.Println("Error stopping server:", err)
				}
			}
			os.Exit(0)

		default:
			fmt.Println("ERROR: Invalid command")
			continue
		}
	}
}
