#https://gist.github.com/BenSampo/aa5f72584df79f679a7e603ace517c14

if [ -f ~/.profile ]; then
  . ~/.profile
fi

# Break on any error
set -e

SCRIPT_DIR="$(dirname "${BASH_SOURCE[0]}")"  # get the directory name
SCRIPT_DIR="$(realpath "${SCRIPT_DIR}")"     # resolve its full path

# Change to the project directory
cd ${SCRIPT_DIR}/..

# Load .env variables
source .env

# Pull the latest changes from the git repository
git pull
