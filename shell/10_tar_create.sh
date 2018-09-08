#!/bin/sh

cd ../../
echo '作業ディレクトリ'
pwd
echo 'cb_red_bookを圧縮開始'
tar cvzf cb_red_book.tar.gz cb_red_book
echo 'cb_red_book.tar.gzを作成'
echo "------------ 終わり"
cmd /k