import * as fs from "fs";
import * as path from "path";
const packagePath = path.join(__dirname, "package.json");

const incrementVersion = () => {
    const now = new Date().toLocaleString();
    const packageJson = JSON.parse(fs.readFileSync(packagePath, "utf8"));

    const versionParts = packageJson.version
        .split(".")
        .map((part) => parseInt(part, 10));

    versionParts[2] += 1;
    const newVersion = versionParts.join(".");

    packageJson.version = newVersion;
    packageJson.lastUpdated = now;

    fs.writeFileSync(packagePath, JSON.stringify(packageJson, null, 2) + "\n");
};

incrementVersion();
