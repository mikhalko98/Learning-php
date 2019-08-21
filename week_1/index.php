<?php
$data = <<< TMAKR
Article:
    Header: BMW AG
    Body: Bayerische Motoren Werk is a German multinational company which currently produces automobiles and motorcycles, and also produced aircraft engines until 1945. The history of the name itself begins with Rapp Motorenwerke, an aircraft engine manufacturer. In April 1917, following the departure of the founder Karl Friedrich Rapp, the company was renamed Bayerische Motoren Werk
    ChangeMap:
        Bayerische Motoren Werk: Bavarian Motor Works(BMW)
    Tags: Automotive Industry, Germany, Luxury
Article:
    Header: Volkswagen
    Body:  Volkswagen is a German automaker founded on 28 May 1937 by the German Labour Front, and headquartered in Wolfsburg. Volkswagen was established in 1937 by the German Labour Front in Berlin.
    ChangeMap:
        Volkswagen: VW
        German: GR
    Tags: Automotive Industry, Germany, Not-Luxury
Article:
    Header: Project E
    Body: Project E was a joint project between the United States and the United Kingdom during the Cold War to provide nuclear weapons to the Royal Air Force (RAF) until sufficient British nuclear weapons became available. It was subsequently expanded to provide similar arrangements for the British Army of the Rhine
    ChangeMap:
        Project E: P.E.
    Tags: Military Industry, USA
Article:
    Header: Ford Motor Company
    Body: Ford Motor Company is a multinational automaker that has its main headquarter in Dearborn, Michigan, a suburb of Detroit. It was founded by Henry Ford and incorporated on June 16, 1903.
    ChangeMap:
        Ford Motor Company: Ford
    Tags: Automotive Industry, USA, Not-Luxury
Article:
    Header: Studebaker US6
    Body: The Studebaker US6 (G630) was a series of 2½-ton 6x6 and 5-ton 6x4 trucks manufactured by the Studebaker Corporation and REO Motor Car Company during World War II. The basic cargo version was designed to transport a 5,000 lb (2,300 kg) cargo load over all types of terrain in all kinds of weather.
    Tags: Heavy Automotive Industry, USA, Not-Luxury
TMAKR;

// Виделяю с Article - Header, Body, ChangeMap, Tags
function stringParse($article_string)
{
    if (strpos($article_string, "ChangeMap:")) {
        $ar = preg_split("/(Header:|Body:|ChangeMap:|Tags:)/", $article_string);
        array_shift($ar);                                                                   //сокращаем размер $ar на один елемент(сдвиг массива влево)
        list($article["Header"], $article["Body"], $article["ChangeMap"], $article["Tags"]) = $ar;
    } else {
        $ar = preg_split("/(Header:|Body:|Tags:)/", $article_string);
        array_shift($ar);                                                                   //сокращаем размер $ar на один елемент(сдвиг массива влево)
        list($article["Header"], $article["Body"], $article["Tags"]) = $ar;

    }
    return $article;
}

//Выбираю все Article у которих есть  Tags: Automotive Industry
function article_choose_by_tag($article_list, $main_tag)
{
    $new_article_list = array();
    foreach ($article_list as $value) {
        $article = stringParse($value);
        foreach (explode(",", $article["Tags"]) as $tag) {
            if (!strcmp(trim($tag), $main_tag)) {
                $new_article_list[] = $article;
                break;
            }
        }
    }
    return $new_article_list;
}

//Заменяю в Body необходимые значениями из ChangeMap
function change_map($articles)
{
    for ($i = 0; $i < count($articles); $i++) {
        $list_to_split = explode("\n", $articles[$i]['ChangeMap']);
        foreach ($list_to_split as $one) {
            $change_ = explode(':', $one);
            $name = $change_[0];
            $new_name = next($change_);
            $articles[$i]["Body"] = str_replace(trim($name), trim($new_name), $articles[$i]["Body"]);
        }
    }
    return $articles;
}

function filter_tags($articles)
{
    $tag_list = array();
    foreach ($articles as $article) {
        $tag_article = explode(',', $article["Tags"]);
        foreach ($tag_article as $val) {
            if (!in_array(trim($val), $tag_list)) {
                $tag_list[] = trim($val);
            }
        }
    }
    return $tag_list;
}

$article_list = explode("Article:", $data);
array_shift($article_list);                                         //сокращаем размер $ar на один елемент(сдвиг массива влево)
$articles = article_choose_by_tag($article_list, "Automotive Industry");
$articles = change_map($articles);
$tags = filter_tags($articles);

//строим html-страницу
echo "<html>
<head>
    <title>PHP</title>
</head>
<body>
    <h1>Headers</h1>
    <table-of-content>";
for ($i = 0; $i < count($articles); $i++) {
    echo "<div>", $articles[$i]["Header"], "</div>";
};
echo "</table-of-content>";
echo "<content>";
foreach ($articles as $article) {
    echo "<article>", "<h1>", $article["Header"], "</h1>";
    echo "<p>", $article["Body"], "</p>", "</article>";
};
echo "</content>";
echo "<tags>";
echo $tags[0];
for ($i=1; $i<count($tags); $i++)
    echo ', ', $tags[$i];
echo "</tags>";

echo "</body>
</html>";
?>