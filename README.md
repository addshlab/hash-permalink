![NO SUPPORT](http://add.sh/images/no-support.png) ![NO GUARANTEE](http://add.sh/images/no-guarantee.png) [![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

# Hash Permalink :: Make WordPress permalink a random hash

## 注意

このソフトウェアはサポート無し・動作保証無しです。第三者からの要望は受け付けていません。

## 説明

WordPress のパーマリンクをハッシュ値にするプラグインです。


## 仕組み

1. サイト公開時に、サイトURL, 投稿 ID, 投稿日時, 投稿ユーザー名 の連結文字列から sha1 と md5 のハッシュ値を生成
2. sha1 ハッシュの最初の5文字と md5 ハッシュの最初の5文字を連結した10文字の文字列を wp_posts -> post_name に設定

## 仕様

* sha1 と md5 を併用するのは、衝突の可能性を下げるため
* wp_posts -> guid に 'sha1.md5' の形式で完全なハッシュ値を保存

# コア機能や他のプラグインに影響を与えうる仕様

* wp_posts -> guid は、保存時に esc_url フィルターで http 付きの文字列に変換されるため、remove_filter でこの機能を除去している



