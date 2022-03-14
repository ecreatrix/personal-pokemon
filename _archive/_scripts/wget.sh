DIR="/Volumes/PoolZ1 Backup/alyx/PNP Games/Pokemon/design/assets/pokemons/_scripts"
WGET="/Volumes/PoolZ1 Backup/alyx/PNP Games/Pokemon/design/assets/pokemons/wget"


#mkdir "$WGET/"bulba/original
#touch "$WGET/"bulba/test/errors.txt

#cd "$WGET/"pokedb && wget -i "$DIR/"pokedb-images.txt && \

cd "$WGET/"official && wget --recursive --level=1 --convert-links -H \
  --accept '*.png,*.jpg' \
  --accept-regex https://assets.pokemon.com/* \
  --execute robots=off \
  --no-parent \
  -i "$DIR"/pokedex.txt