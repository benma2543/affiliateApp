<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("config.php");
require_once("user/protect.php");
require_once("user/user-lib.php");

$db = new PDO(
      "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
      USER_DB_USER, USER_DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);



$categoryTags = [
    1 => ['10k', 'audience', 'beginners', 'blueprint', 'buyers', 'campaigns', 'clickfunnel', 'commission', 'commissions', 'convert', 'converting', 'course', 'customers', 'earning', 'ebook', 'formula', 'products', 'free', 'generator', 'website', 'visitors', 'tutorial', 'training', 'website', 'traffic', 'help', 'tracking', 'important', 'income', 'target', 'tactics', 'system', 'sources', 'maximize', 'methods', 'networking', 'offline', 'online', 'own', 'pages', 'paid', 'power'],
    2 => ['build', 'earning', 'products', 'youtube', 'webinar', 'video', 'videos', 'help', 'methods'],
    3 => ['ad', 'ads', 'advertising', 'audience', 'boost', 'campaigns', 'offline', 'online'],
    4 => ['audio', 'background', 'cinematic', 'clips', 'music', 'melodies', 'melody', 'photos', 'sounds', 'sound', 'timelapse', 'footage', ' graphic', ' graphics', 'sites'],
    5 => ['bonus', 'building', 'business', 'buyers', 'mailing', 'mail', 'email', 'products', 'techniques', 'subscribers', 'leads', 'list', 'squeeze', 'opt-in', 'profit', 'profitable', 'relationship', 'secret'],
    6 => ['blog', 'wordpress', 'wordpress', 'plugin', 'plugins', 'gdpr', 'tactics', 'keywords', 'learning', 'link', 'popup', 'quiz', 'ranked'],
    7 => ['creation', 'customer', 'customers', 'funnel', 'funnels', 'formula', 'visitors', 'tutorial', 'sales'],
    8 => ['affiliate', 'basics', 'beginners', 'commission', 'commissions', 'maximize', 'products', 'report'],
    9 => ['breakthroughs', 'diet', 'dieting', 'dating', 'forex', 'fullbody', 'health', 'juice', 'trading', 'weightloss', 'workout', 'survival', 'defender', 'ecourse', 'emergency', 'growth', 'guide', 'help', 'tips', 'stock', 'stronger', 'steps', 'spyware', 'mind', 'newbies', 'popular', 'positive', 'protect'],
    10 => ['amazon', 'ecommerce', 'dropshipping', 'generator', 'master', 'mastery', 'money', 'ping', 'products', 'profit', 'secret'],
    11 => ['amazons3', 'affiliate', 'answered', 'article', 'articles', 'autopilot', 'basics', 'beginners', 'blog', 'blueprint', 'book', 'bonus', 'boost', 'business', 'buyers', 'campaigns', 'casestudy', 'character', 'clients', 'commission', 'commissions', 'content', 'convert', 'converting', 'copywriting', 'course', 'creation', 'customer', 'customers', 'development', 'dreams', 'entrepreneurial', 'goal', 'goals', 'google', 'write', 'whitelabel', 'training', 'guide', 'toolkit', 'hobby', 'interview', 'knowledge', 'launch', 'leader', 'learning', 'lessons', 'market', 'marketer', 'marketers', 'marketing', 'masters', 'stronger', 'strategies', 'steps', 'software', 'sites', 'seo', 'membership', 'mobile', 'money', 'network', 'networking', 'online', 'organization', 'pack', 'product', 'profit', 'profits', 'publishing', 'recurring', 'report', 'resume'],
    12 => ['book', 'build', 'facebook', 'ebook', 'entrepreneurs', 'pinterest', 'socialmedia', 'free', 'youtube', 'google', 'tracking', 'instagram', 'income', 'target', 'lessons', 'magazine', 'marketing', 'master', 'strategies', 'sponsorship', 'sharing', 'seo', 'mastery', 'methods', 'power', 'quiz', 'recurring', 'secret'],
    13 => ['plr', 'popular', 'private', 'resell']
];

$categoryNumber = isset($_GET['category']) ? (int) $_GET['category'] : 0;

if ($categoryNumber > 0 && isset($categoryTags[$categoryNumber])) {
    $tags = $categoryTags[$categoryNumber];
    $placeholders = rtrim(str_repeat('?, ', count($tags)), ', ');
    $sql = "SELECT * FROM bonuses WHERE bonus_tags REGEXP ?";
    $pattern = implode('|', array_map(function($tag) { return preg_quote($tag, '/'); }, $tags));
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$pattern]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
} else {
    // Handle the "All" category or invalid category selection
    $sql = "SELECT * FROM bonuses";
    $stmt = $db->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
}
?>
