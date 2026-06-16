#!/bin/bash

echo "Cleaning logs..."
rm -rf /opt/lampp/htdocs/phpmysqldemo/src/logs/*

echo "Git add..."
git add .

echo "Git commit..."
git commit -m "Updates $(date '+%Y-%m-%d %H:%M:%S')"

echo "Git push..."
git push origin main

echo "Deploying to server..."

ssh -p 65002 u797036281@82.112.239.225 << 'EOF'
cd /home/u797036281/domains/lightyellow-tarsier-923320.hostingersite.com/public_html && git pull origin main || exit
git pull origin main
echo "Deployment completed."
EOF

echo "Done!"