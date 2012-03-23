##F2ETEST 基于selenium2 & webdriverjs 的前端自动化测试平台

##serverStatus接口数据约定
[
    'chrome',
    'firefox',
    'ie6',
    'ie7',
    'ie8',
    'ie9',
    'opera'
]

##doRequest运行结果数据
{
    'result': true,
    'data': {
        'type': 'ie6',
        'logs': {
            'msg': 'abcdefghij',
            'level': '1' //为1,2,3
        }
    },
    'screen': 'http:///a.com/sss/1.jpg'
}