<?php

namespace AppBundle\DataFixtures\ORM;

// use Hautelook\AliceBundle\Alice\DataFixtureLoader; // v0.2;
use Hautelook\AliceBundle\Doctrine\DataFixtures\AbstractLoader;
use Faker\Factory;

/**
 * テストデータ投入処理
 */
class EntitiesDataFixtureLoader extends AbstractLoader
{
    private $faker;

    private $posts = array();
    private $comments = array();

    public function __construct()
    {
        $this->faker = Factory::create('ja_JP');

        $this->posts = array(
            "第二話「偽りの戦端」" => "セイバー「貴方と切嗣が目指す物は、正しいと思います」\nライダー「覇王は二人と要らんからな」\n切嗣「父さんが約束するよ」\nギル「貴様は我を見るにあたらん」\n龍之介「超COOLだよアンタ！」\n綺礼「速やかに遠坂時臣を――――――抹殺しろ」",
            "第三話「冬木の地」" => "璃正「いよいよ始まるぞ、第四次聖杯戦争が」\nギル「度し難い程に醜悪だ」\n切嗣「預けておいたやつは、どこだ」\nライダー「盛り上がってきたわい」\nランサー「良くぞ来た」\nアイリ「この私に、勝利を！」\nセイバー「はい、必ずや」",
            "第四話「魔槍の刃」" => "切嗣「では――――――お手並み拝見だ。かわいい騎士王さん」\nランサー「セイバーのクラスの抗魔力は伊達ではないか」\nセイバー「この男…できる！」\nケイネス「じゃれ合いはそこまでだランサー」\nアイリ「何が起こっているの…！？」\nウェイバー「行きます、連れていきます！」",
            "第五話「凶獣咆吼」" => "ライダー「我が名は征服王イスカンダル！」\nウェイバー「ステータスも何も全然読めない！」\nセイバー「アイリスフィール、下がって！」\n切嗣「あれが、奴の宝具」\nケイネス「令呪を以て命ずる」\nアイリ「貴方のマスターを信じて！」\nキャスター「ついに復活を遂げたぁ！」\nギル「最早肉片一つも残さぬぞ！」",
            "第六話「謀略の夜」" => "ランサー「あのセイバーの首はお約束いたします」\nセイバー「次は手加減抜きで斬る！」\nソラウ「履き違えてるのは貴方ではなくて？」\nケイネス「ケイネス・エルメロイの魔術工房を堪能してもらおうではないか」\n綺礼「私に説法する気か」\nキャスター「お迎えにあがりました聖処女よ」",
            "第七話「魔境の森」" => "璃正「暫定的ルール変更を設定する」\nアイリ「衛宮切嗣を、殺してしまう…」\n切嗣「逃げられる！！今ならばまだ！！」\nランサー「セイバーの首級は、我が槍の勲！」\nセイバー「貴様と聖杯を競おうとは、思わない！」\nキャスター「思い上がるなよこの匹夫めがァ！！」",
            "第八話「魔術師殺し」" => "アイリ「舞弥さん、駄目！」\nケイネス「魔術師の面汚しが…」\nキャスター「恐怖なさい、絶望なさい」\nランサー「巡れ！ゲイ・ジャルグ！」\n綺礼「お前達は、誰の意思で戦った」\nセイバー「覚悟はいいな、外道」",
            "第九話「王と従者」" => "ライダー「なんせ余のマスターが、殺されかかってるんだからな！」\n時臣「戦いにも品格が求められるのだ」\nランサー「その申し出は、承諾出来ない」\nソラウ「この令呪を私に譲って頂戴。」\nケイネス「ランサーはそんな殊勝な奴じゃない！」",
            "第十話「凛の冒険」" => "時臣「魔力を一度に注入しすぎたんだ」\n凛「私にだって手伝えることはきっとあるわ！」\n葵「まさか冬木に行ったなんて…」\n雁夜「これが、間桐の魔術だよ\n龍之介「今から俺らパーティ始めるところなんだよね」\n凛「こんなんじゃ、何にもできない！」",
            "第十一話「聖杯問答」" => "ライダー「無欲な王など、飾り物にも劣るわい！」\nセイバー「力無き者を守らずしてどうする！」\n時臣「我々が失うものは何もない」\nアイリ「固有結界ですって！？」\nギル「貴様はこの我が手ずから殺す」\nウェイバー「望みは世界征服だったっちゅっほへっ！」",
            "第一二話「聖杯の招き」" => "時臣「此処から先は第二局面だ」\nアイリ「私は普通の人間ではなくてよ？」\nセイバー「そんな簡単に済ませていい話ではないでしょう！」\nギル「求めるところを為すがいい、道は示されているぞ？」\n綺礼「だがそれは、罰せられるべき悪徳だ！！」",
            "第一三話「禁断の狂宴」" => "ライダー「一番槍は、いただくぞ！」\nソラウ「仕留めるなら、今が絶好のチャンスね」\nセイバー「決着を付けるぞ！」\n龍之介「きっとこの世界は神様の愛に満ちているよ」\nキャスター「今再び、我らは救世の旗を掲げよう！！」",
            "第十四話「未遠川血戦」" => "龍之介「やっちまえぇ青髭の旦那ァっ！」\n璃正「この様な失態は前代未聞」\nギル「我が至宝たるエアをここで抜けと！？」\nウェイバー「セイバー！」\n時臣「英雄王、どうかお待ちを！」\nライダー「なんて奴だ」\n雁夜「一人残らず、殺し尽くす！」",
            "第十五話「黄金の輝き」" => "キャスター「おぉ、この光は…」\nギル「無駄な足掻きよ」\nアイリ「常勝の王は高らかに、手に取る奇跡の真名を謳う」\nライダー「痛ましくて見るに耐えぬ」\n綺礼「死滅する気か」\nセイバー「ここで食い止めなければ！」\nランサー「頼んだぞ、セイバー」",
            "第十六話「栄誉の果て」" => "ランサー「フィオナ騎士団が一番槍、ディルムッド・オディナ！俺は…お前に出会えてよかった！」\nセイバー「おうとも、ブリテン王、アルトリア・ペンドラゴンが受けて立つ。いざ！」\nランサー「推して参る！」",
            "第十七話「第八の契約」" => "時臣「あってはならない事だ！」\nセイバー「同盟ですか、今になって」\n綺礼「聖杯戦争の真実を教えてやろう」\nアイリ「この話、受けましょう」\n舞弥「あの人の夢を叶えるために死んでください」\nギル「ふん、興ざめな幕切れだ」\n凛「行ってらっしゃい、お父様」",
            "第十八話「遠い記憶」" => "シャーレイ「ケリィはさ、どんな大人になりたいの？」\nシモン「あの屋敷に行くのは、やめなさい」\nケリィ「なんだ、これ…」\nナタリア「後の事は自分で考えな」\n矩賢「お前にはまだ早すぎる」\nシャーレイ「いつか君が手に入れるのは、世界を変える力だよ。」",
            "第十九話「正義の在処」" => "あの島を出て数年の歳月を\n僕は、あの島から僕を連れ出したナタリア・カミンスキーの元で過ごした。\nそれは、つまりナタリアと同じ道。すなわち、狩人として生きていくということを意味する。",
            "第二十話「暗殺者の帰還」" => "雁夜「お前は誰だ」\nライダー「悪い冗談にも程がある」\n臓硯「どうだ、桜から奪ったその命」\n切嗣「令呪を以て我が傀儡に命ず！」\n舞弥「戻りましたね、昔のあなたの顔に」\nウェイバー「これは僕が始めた戦いだ」\nアイリ「私はね…幸せだよ…」",
            "第二十一話「双輪の騎士」" => "切嗣「アイリスフィールはどこだ」\n綺礼「忘れるなよ、今夜、令呪だ」\nライダー「我が疾走を阻むものはない」\nセイバー「侮るなよ、征服王！」\n雁夜「そいつのせいで！その男さえいなければ！」\n臓硯「あぁ、怖い怖い」\n葵「満足してる？雁夜君…」",
            "第二十二話「この世の全ての悪」" => "ウェイバー「令呪を以て、命ずる」\nライダー「刮目して見届けよ！」\nギル「今宵の我は手加減抜きで行く」\n切嗣「速やかに、排除するのみ…」\n綺礼「私と奴とはどう違う？迷い人でなくてなんなのだ！」\nアイリ「ここにいる私は…誰…？」",
            "第二十三話「最果ての海」" => "ライダー「敵は万夫不当の英雄王、相手にとって不足なし！我らが覇道を示そうぞ！」\nギル「征服王、見果てぬ夢の結末を知るがいい。この我が手ずから理を示そう」",
            "第二十四話「最後の令呪」" => "セイバー「私の聖杯を奪うのか！」\nギル「何のつもりだセイバー！」\nランスロット「困ったお方だ」\n綺礼「そんな馬鹿な！あれは自らの命を、誕生を望んでいる！」\nイリヤ「お帰りなさい、切嗣！」\nアイリ「きっと来てくれると思ってた」",
            "第二十五話「Fate/Zero」" => "その顔を覚えている。\n目に涙を溜めて、心の底から喜んでいる、男の姿。\n男は何かに感謝するように、「ありがとう」と言った。",
            "Unlimited Black Workers〜無限の社畜共〜" => "体は仕事で出来ている。\n計画は遅延で、心は病。\n幾たびの仕変を超えて延期。\nただの一度も納品もなく、ただの一度も休暇は取れない。\n担当は常に独り。仕事の山で残業に酔う。\nならば我が障害に復旧は不要ず。\nその体は、きっと仕事で出来ていた。",
        );

        $this->comments = array(
            "ランサーが過労で死んだ！",
            "そこまでにしておけよ株主",
            "残業せずして何が社畜か！",
            "体は仕事で出来ている",
            "セイバーは女の子だから働いちゃダメだ！",
            "約束された商談の件",
            "――行くぞ課長。有休の用意は十分か",
            "双方ペンを収めよ社長の御前であるぞ",
            "遠坂株式会社を恐れる必要はないと？",
            "全て遠き理想郷(ゴールデンウィーク)",
            "この人でなし！！",
            "残業戦争",
            "王の財宝（ダツゼイ）",
            "誰の許しを得ておもてをあげる　高卒！",
            "掌理すべき黄金の週",
            "ステイナイト（残業）",
            "バイト風情が正社員にたてつくかッ",
            "ああ、サビ残するのはいいが\n別に残業代は払わなくても構わんのだろう？",
            "別に、無断に休んでもかまわんのだろう？",
            "残業代/Zero",
            "何を言う。私は君が内定を出した社員だ。それが使えないはずがない",
            "別に終わらせてから、帰っても構わんのだろう?",
            "なあ新卒よいい加減にそのいたましき夢から覚めよ\nさもなくば貴様は社会人として最低限の誇りさえも失うハメになる",
            "部長「残業代など出るわけなかろう」\n社畜「「然り！然り！」」",
            "残業しろランサー",
            "ご覧の通り、貴様が挑むのは無限の営業。労働の極地！\n恐れずしてかかってこい！！",
            "理想を抱いて残業しろ",
            "僕はね、プロ野球選手になりたかったんだ",
            "Unlimited Black Companies",
            "Unlimited Black Works",
            "私が御社で望むのは前社で叶わなかった忠節への道\nもうあんな悲運を繰り返したくない",
            "常に余裕を持って優雅たれ\nビジネスにも品格が求められるのだ",
            "キャスター「仕事というものには期限があります」",
            "ルールブレイカー（仕様変更）",
            "体は仕事で出来ている。",
            "血潮は名刺で　心は接待。",
            "幾たびの面接を越えて内定",
            "ただの一度もクーデターはなく、",
            "ただの一度も理解されない。",
            "彼の者は常に独り　仕事の山で残業に酔う。",
            "故に、生涯に意味はなく。",
            "その体は、きっと仕事で出来ていた。",
            "ボールを相手のゴールにシュゥゥゥーッ!!",
            "超!エキサイティン!!",
            "3Dアクションゲーム!",
            "バトルドーム、ツクダオリジナルから。",
        );
    }

    /**
     * YAMLを読み込んでテストデータを投入する
     */
    public function getFixtures()
    {
        return array(
            '@AppBundle/Resources/fixtures/posts.yml',
            '@AppBundle/Resources/fixtures/comments.yml',
        );
    }

    public function title()
    {
        return array_rand($this->posts);
    }

    public function content($title)
    {
        return $this->posts[$title];
    }

    public function comment()
    {
        return $this->comments[array_rand($this->comments)];
    }

    public function getPost()
    {
        $post = $this->getRepository('AppBundle:Post')->getRandomEntity();
        return $post;
    }

    private function getRepository($repositoryName)
    {
        return $this->container->get('doctrine')->getManager()->getRepository($repositoryName);
    }
}
