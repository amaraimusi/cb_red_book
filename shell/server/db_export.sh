#!/bin/bash

echo "DBパスワードを入力してください"
mysqldump -Q -h mysql716.db.sakura.ne.jp -u amaraimusi -p amaraimusi_cb_red_book > www/cb_red_book/shell/cb_red_book.sql 2> www/cb_red_book/shell/dump.error.txt

echo "出力完了"