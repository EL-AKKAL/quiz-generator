import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const packagePath = path.join(__dirname, "package.json");

const incrementVersion = () => {
    const now = new Date().toLocaleString();
    const packageJson = JSON.parse(fs.readFileSync(packagePath, "utf8"));

    const versionParts = packageJson.version
        .split('.')
        .map((part) => Number.parseInt(part, 10));

    versionParts[2] += 1;
    const newVersion = versionParts.join(".");

    packageJson.version = newVersion;
    packageJson.lastUpdated = now;

    fs.writeFileSync(packagePath, JSON.stringify(packageJson, null, 2) + "\n");
};

incrementVersion();
