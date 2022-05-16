const fs = require("fs");

function isDir(dir) {
    const folders = dir.split("/");
    let path = ".";
    folders.forEach(folder => {
        if (folder !== "." && folder !== "") {
            path += `/${folder}`;
            if (!fs.existsSync(path)) {
                fs.mkdirSync(path);
            }
        }
    });
}

function writeFile(arr_data, filename = "mnemonic.txt", dir = "wallet") {
    const dirFolder = `./backup/${dir}`;
    isDir(dirFolder);
    const path = dirFolder + "/" + filename;
    const data = "\n" + arr_data.join("\n");
    try {
        if (fs.existsSync(path)) {
            return fs.open(path, "w", function(err, file) {
                if (!err) {
                    fs.appendFile(path, data, function(err) {});
                }
            });
        } else {
            return fs.writeFileSync(path, data, function(err) {});
        }
    } catch (error) {
        console.error("error", error);
    }
}

function readFile(filename = "mnemonic.txt", dir = "wallet") {
    try {
        const dirFolder = `./backup/${dir}`;
        const file = fs.readFileSync(`${dirFolder}/${filename}`, {
            encoding: "utf8",
            flag: "r"
        });
        return file;
    } catch (error) {
        return null;
    }
}

module.exports = { writeFile, readFile };
