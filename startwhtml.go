package main

import (
	"bufio"
	"bytes"
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"os"
	"strconv"
	"strings"
)

type Data struct {
	Publika bool `json:"publika"`
	Polowa  bool `json:"polowa"`
	Telefon bool `json:"telefon"`
}

var data = Data{true, true, true}
var listening bool = false

func handler(w http.ResponseWriter, r *http.Request) {
	defer r.Body.Close()

	if !listening {
		fmt.Fprintf(w, "Server not listening for data yet. Use 'listen' command.\n")
		return
	}

	body, err := io.ReadAll(r.Body)
	if err != nil {
		fmt.Fprintf(w, "Error reading request body: %v\n", err)
		return
	}
	fmt.Println(body)

	var newData []bool
	err = json.Unmarshal(body, &newData)
	if err != nil || len(newData) != 3 {
		fmt.Fprintf(w, "Invalid data format. Please send an array of 3 booleans.\n")
		return
	}

	data.Publika = newData[0]
	data.Polowa = newData[1]
	data.Telefon = newData[2]

	saveData()
	fmt.Fprintf(w, "Data saved successfully.\n")
}

func saveData() {
	var buffer bytes.Buffer

	fmt.Fprintf(&buffer, `{"publika":"%s","polowa":"%s","telefon":"%s"}`, strconv.FormatBool(data.Publika), strconv.FormatBool(data.Polowa), strconv.FormatBool(data.Telefon))

	jsonData := buffer.Bytes()

	err := os.WriteFile("src/assets/json/kola_ratunkowe.json", jsonData, 0644)
	if err != nil {
		fmt.Println("Error saving data to file:", err)
	}
}

func resetData() {
	data = Data{true, true, true}
	listening = false
	saveData()
	fmt.Println("Data reset to defaults and server stopped listening for new data.")
}

// Function to serve static content from "src" directory
func serveStaticContent(w http.ResponseWriter, r *http.Request) {
	// Use http.FileServer to serve files from the "src" directory
	fs := http.FileServer(http.Dir("src"))
	fs.ServeHTTP(w, r)
}

func main() {

	fmt.Println("Starting server...")

	// Register handlers for desired ports
	http.HandleFunc("/", serveStaticContent) // Port 8192 for web content
	http.HandleFunc("/data", handler)        // Port 7964 for data updates

	// Start server on port 8192
	go func() {
		fmt.Println("Server listening on port 8192 for web content.")
		err := http.ListenAndServe(":8192", nil)
		fmt.Println(err)
	}()

	// Start server on port 7964 for data handling
	go func() {
		fmt.Println("Server listening on port 7964 for data updates.")
		err := http.ListenAndServe(":7964", nil)
		fmt.Println(err)
	}()

	// Command line interface for control
	reader := bufio.NewReader(os.Stdin)
	fmt.Println("Available commands: listen, reset, exit")
	for {
		command, err := reader.ReadString('\n')
		if err != nil {
			fmt.Println("Error reading command:", err)
			return
		}

		command = strings.TrimSpace(command)
		switch command {
		case "listen":
			listening = true
			fmt.Println("Server now listening for data.")
		case "reset":
			resetData()
		case "exit":
			os.Exit(0)
		default:
			fmt.Println("Invalid command. Available commands: listen, reset, exit")
		}
	}
}
