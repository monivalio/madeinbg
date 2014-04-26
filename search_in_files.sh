FOLDER=$1
STRING=$2

find "$FOLDER" -type f | while read FILE
do
  if grep -i --quiet "$STRING" "$FILE"; then
    echo $FILE
    cat $FILE|grep -i "$STRING"
    echo -e "\n"
  fi
done
