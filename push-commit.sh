#!/bin/bash

# Stop if any command fails
set -e

BRANCH=$(git branch --show-current)

echo "⬇️ Pulling latest changes..."
git pull origin $BRANCH --rebase

echo "⬆️ Pushing..."
git push origin $BRANCH

echo "✅ Done."
