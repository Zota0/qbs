const http = require('http');
const fs = require('fs');

let data = {
    publika: true,
    polowa: true,
    telefon: true,
};

let listening = false;

function handler(req, res) {
    if (!listening) {
        res.end("Server not listening for data yet. Use 'listen' command.\n");
        return;
    }

    let body = '';
    req.on('data', (chunk) => {
        body += chunk;
    });

    req.on('end', () => {
        try {
        const newData = JSON.parse(body);
        if (!Array.isArray(newData) || newData.length !== 3) {
            res.end("Invalid data format. Please send an array of 3 booleans.\n");
            return;
        }
        data.publika = newData[0];
        data.polowa = newData[1];
        data.telefon = newData[2];
        saveData();
        res.end("Data saved successfully.\n");
        } catch (err) {
        res.end("Error reading request body: " + err.message + "\n");
        }
    });
}

function saveData() {
    let data2 = {
        publika: data['publika'].toString(),
        polowa: data['polowa'].toString(),
        telefon: data['telefon'].toString()
    };

    const jsonData = JSON.stringify(data2, null, 4);

    fs.writeFile('src/assets/json/kola_ratunkowe.json', jsonData, (err) => {
        if (err) {
        console.error("Error saving data to file:", err);
        }
    });
}

function resetData() {
    data = {
        publika: true,
        polowa: true,
        telefon: true,
    };
    listening = false;
    saveData();
    console.log("Data reset to defaults and server stopped listening for new data.");
}

console.log("Available commands: listen, reset, exit");

process.stdin.on('data', (input) => {
    const command = input.toString().trim();
    switch (command) {
        case 'listen':
        listening = true;
        console.log("Server now listening for data.");
        break;
        case 'reset':
        resetData();
        break;
        case 'exit':
        case 'stop':
        process.exit(0);
        break;
        default:
        console.log("Invalid command. Available commands: listen, reset, exit");
    }
});
