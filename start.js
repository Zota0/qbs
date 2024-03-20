export async function WriteJSON(filepath = 'message.json',json = null) {
    const controller = new AbortController();
    const { signal } = controller;

    try {
        const StringData = JSON.stringify(json);
        await WF.writeFile(filepath, StringData, { signal });
    } catch (err) {
        if(err.name === 'AbortError') {
            console.log("ERROR: -write operation aborted --#" + err);
        } else {
            console.log("ERROR: -when Writing file --#" + err);
        }
    } finally {
        controller.abort();
    }
}