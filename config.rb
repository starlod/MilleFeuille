require 'compass/import-once/activate'
# アディショナルCompassプラグインを利用するなら以下に書く

# プロジェクトのディレクトリをここにセットする
http_path       = '/'
css_dir         = 'src/App/BlogBundle/Resources/public/css'    # CSSを書き出すディレクトリ
sass_dir        = 'src/App/BlogBundle/Resources/public/scss'   # SASSファイルがあるディレクトリ
images_dir      = 'src/App/BlogBundle/Resources/public/images' # 画像用のディレクトリ
javascripts_dir = 'src/App/BlogBundle/Resources/public/js'     # JavaScriptファイル用のディレクトリ
cache_dir       = '../.sass-cache'                             # キャッシュ用のディレクトリ

# アウトプットスタイルの選択（利用するスタイルのコメントを外して利用する）（デフォルト：expanded）
# output_style = :expanded   # 通常のアウトプットスタイル
# output_style = :nested     # Sassなどのネストを継承したスタイル
# output_style = :compact    # 1つのCSSCSSレイクタの設定が１行になるスタイル
# output_style = :compressed # コメントを取り除いて完全に圧縮するスタイル
output_style = (environment == :production) ? :compressed : :expanded

# Compassで拡張された関数で使うURLを、絶対パスか相対パスかを指定（デフォルト：false[絶対パス]）
relative_assets = true

# キャッシュを出力するか（デフォルト：true[出力する]）
# cache = false

# デバッグ用のコメントを出力するか（デフォルト：true[出力する]）
# line_comments = false

# SCSS記法とSass記法の切り替え
# Sass記法を利用する場合は、以下のコメントを外してください
# preferred_syntax = :sass
