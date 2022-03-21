DIR="/Volumes/PoolZ1 Backup/alyx/PNP Games/Pokemon/design/assets/pokemons/_scripts"

wget --recursive --level=1 --convert-links -H \
  --accept '*.png,*.jpg' \
  --accept-regex https://assets.pokemon.com/* \
  --execute robots=off \
  --no-parent \
  -i "$DIR"/pokedex.txt