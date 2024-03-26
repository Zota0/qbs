package main

import (
	"fmt"
	"net/http"
	"os/exec"
)

func StartServer() {
	cmd := exec.Command("./server.exe")
	err := cmd.Start()
	if err != nil {
		fmt.Println("ERROR: running `server.exe`: ", err)
	}
}

func main() {

	staticDir := http.Dir(".")
	handler := http.FileServer(staticDir)

	http.Handle("/", handler)

	StartServer()

	http.ListenAndServe(":8192", nil)
	fmt.Println("NOTICE: Server listening on port 7964")
}
