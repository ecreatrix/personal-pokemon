DIR="/Volumes/Files/_Business/Web/laravel/pokemon/notes/"
WGET="/Volumes/Files/External/PNP Games/Pokemon/design/assets/pokemons/wget"

mkdir "$WGET/"{bulba,pokedb}

#cd "$WGET/"pokedb && wget -i "$DIR/"pokedb-images.txt && \
cd "$WGET/"bulba && wget --recursive --level=1 --convert-links -H \
  --accept '*.png,*.jpg' \
  --accept-regex https://archives.bulbagarden.net/* \
  --execute robots=off \
  --no-parent \
  -i "$DIR/"bulba-images.txt