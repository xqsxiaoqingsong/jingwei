<?php
$configs = array(
    'TAGLIB_BUILD_IN' => THINKCMF_CORE_TAGLIBS . ',Portal\Lib\Taglib\Portal',
    'HTML_CACHE_RULES' => array(
        // 定义静态缓存规则
        // 定义格式1 数组方式
        'article:index' => array('portal/article/{id}',600),
        'index:index' => array('portal/index',600),
        'list:index' => array('portal/list/{id}_{p}',60)
    ),
     /* 七牛上传下载 */
    'UPLOAD_FILE_QINIU'     => array (
    'driver'            => 'Qiniu',//七牛驱动
    'driverConfig'      => array (
        'secretKey'         => '7BT5cPy9SyX23yClaCgURvqlYdmp5eT_9d6fhPuL',
        'accessKey'         => 'p53TdxExussF8VFd2b1cL7JXZz4tVd5sVmuR8xyF',
        'domain'            => 'pc5hsiop1.bkt.clouddn.com',
        'bucket'            => 'guoji-cloud',
    )
),
);

return array_merge($configs);
