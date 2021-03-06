<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Add page TS config
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="DIR:EXT:t3extblog/Configuration/TSconfig/" extensions="tsconfig">'
);

// Plugins
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'FelixNagel.t3extblog',
    'Blogsystem',
    [
        'Post' => 'list, tag, category, author, show, permalink, preview',
        'Comment' => 'create, show',
    ],
    // non-cacheable actions
    [
        'Post' => 'permalink, preview',
        'Comment' => 'create',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'FelixNagel.t3extblog',
    'Archive',
    [
        'Post' => 'archive',
    ],
    // non-cacheable actions
    [
        'Post' => '',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'FelixNagel.t3extblog',
    'Rss',
    [
        'Post' => 'rss',
    ],
    // non-cacheable actions
    [
        'Post' => '',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'FelixNagel.t3extblog',
    'SubscriptionManager',
    [
        'Subscriber' => 'list, error, logout',
        'PostSubscriber' => 'list, delete, confirm',
        'BlogSubscriber' => 'list, delete, confirm, create',
    ],
    // non-cacheable actions
    [
        'Subscriber' => 'list, error, logout',
        'PostSubscriber' => 'list, delete, confirm',
        'BlogSubscriber' => 'list, delete, confirm, create',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'FelixNagel.t3extblog',
    'BlogSubscription',
    [
        'BlogSubscriberForm' => 'new, create, success',
    ],
    // non-cacheable actions
    [
        'BlogSubscriberForm' => 'new, create, success',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'FelixNagel.t3extblog',
    'Categories',
    [
        'Category' => 'list, show',
    ],
    // non-cacheable actions
    [
        'Category' => '',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'FelixNagel.t3extblog',
    'LatestPosts',
    [
        'Post' => 'latest',
    ],
    // non-cacheable actions
    [
        'Post' => '',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'FelixNagel.t3extblog',
    'LatestComments',
    [
        'Comment' => 'latest',
    ],
    // non-cacheable actions
    [
        'Comment' => '',
    ]
);

if (TYPO3_MODE == 'BE') {
    // Add BE hooks
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
        \FelixNagel\T3extblog\Hooks\Tcemain::class;

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] =
        \FelixNagel\T3extblog\Hooks\Tcemain::class;
}

// Add cHash configuration
// See: http://forum.typo3.org/index.php?t=msg&th=203350
$requiredParameters = [
    'tx_t3extblog_blogsystem[controller]',
    'tx_t3extblog_blogsystem[action]',
    'tx_t3extblog_blogsystem[post]',
    'tx_t3extblog_blogsystem[permalinkPost]',
    'tx_t3extblog_blogsystem[previewPost]',
    'tx_t3extblog_blogsystem[tag]',
    'tx_t3extblog_blogsystem[category]',
    'tx_t3extblog_blogsystem[author]',
    'tx_t3extblog_blogsystem[@widget_0][currentPage]',

    'tx_t3extblog_subscriptionmanager[controller]',
    'tx_t3extblog_subscriptionmanager[action]',
    'tx_t3extblog_subscriptionmanager[subscriber]',
    'tx_t3extblog_subscriptionmanager[code]',
];
$GLOBALS['TYPO3_CONF_VARS']['FE']['cHashRequiredParameters'] .= ','.implode(',', $requiredParameters);

// Make sure post preview works, taken from EXT:tt_news
$configuredCookieName = trim($GLOBALS['TYPO3_CONF_VARS']['BE']['cookieName']);
if (empty($configuredCookieName)) {
    $configuredCookieName = 'be_typo_user';
}
if ($_COOKIE[$configuredCookieName]) {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFoundOnCHashError'] = 0;
}

// Make default avatar provider available in FE
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['avatarProviders']['defaultAvatarProvider'] = [
    'provider' => \TYPO3\CMS\Backend\Backend\Avatar\DefaultAvatarProvider::class,
];

// Overwrite classes
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager::class] = [
    'className' => \FelixNagel\T3extblog\Configuration\BackendConfigurationManager::class,
];

// Routing
$GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['T3extblogPostMapper'] =
    \FelixNagel\T3extblog\Routing\Aspect\PostMapper::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['T3extblogPostTagMapper'] =
    \FelixNagel\T3extblog\Routing\Aspect\PostTagMapper::class;

// Logging
$logLevel = \TYPO3\CMS\Core\Core\Environment::getContext()->isDevelopment() ?
    \TYPO3\CMS\Core\Log\LogLevel::DEBUG : \TYPO3\CMS\Core\Log\LogLevel::ERROR;
$GLOBALS['TYPO3_CONF_VARS']['LOG']['FelixNagel']['T3extblog']['writerConfiguration'] = [
    $logLevel => [
        \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
            'logFileInfix' => 't3extblog',
        ],
    ],
];
