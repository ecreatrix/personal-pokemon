FOLDER="other versions/official-pokedex/scrapped-by-no"

sed 1d pokedex/PokedexNo.csv  | while read LINE; do
  NO=$(echo "$LINE" | cut -d$',' -f1)
  NAME=$(echo "$LINE" | cut -d$',' -f2 )
  #echo "$NO" - "$NAME"

  mv "$FOLDER/$NAME".txt "$FOLDER/$NO-$NAME".txt
done