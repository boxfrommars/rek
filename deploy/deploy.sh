# переходим в нужную папку 
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
echo $DIR

cp $DIR/../application/configs/local.ini.bak $DIR/../application/configs/local.ini
mkdir -p $DIR/../tmp
chmod a+rw $DIR/../tmp
chmod a+rw $DIR/../public/files -R 



