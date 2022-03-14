DIR="/Volumes/Files/_Business/Web/laravel/pokemon/notes"
WGET="/Volumes/Poolz1 backup/aLYX/Pnp gaMes/pokemon/design/assets/pokemons/wget"

mkdir "$WGET/"portal-pokemon
cd "$WGET/"portal-pokemon

sed 1d "$DIR"/portal-pokemon-wget.txt  | while read LINE; do
  LINK=$(echo "$LINE" | cut -d$'\t' -f1)
  FILENAME=$(echo -ne "$LINE" | cut -d$'\t' -f2 )
  wget -c $LINK -O $FILENAME
done