<?php

if ((int) TYPO3_branch[0] === 7) {
    $dbConfig = [
        'database' => getenv('DBNAME'),
        'password' => getenv('DBPASS'),
        'username' => getenv('DBUSER'),
        'host' => getenv('DBHOST'),
    ];
} else {
    $dbConfig = [
        'Connections' => [
            'Default' => [
                'charset' => 'utf8',
                'dbname' => getenv('DBNAME'),
                'driver' => 'mysqli',
                'host' => getenv('DBHOST'),
                'password' => getenv('DBPASS'),
                'port' => '3306',
                'user' => getenv('DBUSER'),
            ],
        ],
    ];
}
$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive(
    $GLOBALS['TYPO3_CONF_VARS'],
    ['DB' => $dbConfig]
);

if (!empty(getenv('ENCRYPTION_KEY'))) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] = getenv('ENCRYPTION_KEY');
}

if (!empty(getenv('INSTALL_TOOL_PASSWORD'))) {
    $GLOBALS['TYPO3_CONF_VARS']['BE']['installToolPassword'] = md5(getenv('INSTALL_TOOL_PASSWORD'));
}

if (!empty(getenv('IM_PATH'))) {
    $GLOBALS['TYPO3_CONF_VARS']['GFX']['im_path'] = getenv('IM_PATH');
    $GLOBALS['TYPO3_CONF_VARS']['GFX']['im_path_lzw'] = getenv('IM_PATH');
}

$GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = sprintf(
    '[%s] %s',
    getenv('ENVNAME'),
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename']
);

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class)->connect(
    \TYPO3\CMS\Backend\Backend\ToolbarItems\SystemInformationToolbarItem::class,
    'getSystemInformation',
    \Smichaelsen\Typo3GitlabCi\Slot\SystemInformationToolbarSlot::class,
    'addItems'
);
