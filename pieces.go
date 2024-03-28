package main

import (
	"bufio"
	"bytes"
	"encoding/json"
	"fmt"
	"io"
	"log" // Import for logging
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

	var newData []bool
	err = json.Unmarshal(body, &newData)
	if err != nil {
		fmt.Fprintf(w, "Invalid data format. Please send an array of 3 booleans.\n")
		return
	}

	// Log received data
	log.Println("Received data:", newData)

	// Update data and save regardless of values
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

func main() {
	reader := bufio.NewReader(os.Stdin)
	fmt.Println("Available commands: listen, reset, exit")

	go func() {
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
			case "stop":
				os.Exit(0)
			default:
				fmt.Println("Invalid command. Available commands: listen, reset, exit")
			}
		}
	}()

	http.HandleFunc("/", handler)
	fmt.Println("Server listening on port 7964.")
	err := http.ListenAndServe(":7964", nil)
	fmt.Println(err)
}
