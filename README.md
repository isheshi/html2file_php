项目是java+php

1. php

先看 [网页转文件下载工具](https://blog.csdn.net/heshihu2019/article/details/135875362)



![网页转文件下载前端效果](网页转文件下载前端效果.jpg)

2. java
   起初用这个为了方便learn把一些好文章copy下来，不用java实现的原因是对java不熟悉，用php更快实现功能

   当我尝试java部署mvn clean package后，网页转markdown、word和excel没问题，其他报错了，把test报错行注释掉再mvn clean package

   网页转图片，文章稍微长点一大坨未必会看。。。

之后看下面的部署没啥大问题看，如果你是java小白或许需要看这三篇文章：

[Windows和macOS下安装JDK教程](https://javabetter.cn/overview/jdk-install-config.html#_02%E3%80%81windows-%E5%AE%89%E8%A3%85-jdk)

[搭建第一个Spring Boot项目](https://javabetter.cn/springboot/initializr.html)

[Maven锦集](https://blog.csdn.net/succing/article/details/127281200)

ps：网页转markdown、和网页转word，所以应用实现了这两个功能，前者缺点下载的文章里代码排版会乱，后者不会；后者的缺点是有些不能复制代码的页面，如csdn需要登录才能复制代码，这种文章会下载失败。


[![Build Status](https://travis-ci.org/petterobam/html2file.svg?branch=master)](https://travis-ci.org/petterobam/html2file)
[![MIT Licence](https://badges.frapsoft.com/os/mit/mit.svg?v=103)](https://opensource.org/licenses/mit-license.php)
[![stable](http://badges.github.io/stability-badges/dist/stable.svg)](http://github.com/badges/stability-badges)

# html2file
自己用java写文档转化生成果然有很多弊端和不兼容的地方，而一些开源插件转这些东西还是效果蛮不错，于是心生收集各种插件（兼容windows和linux）并封装成服务的想法。

这里我将收集一系列html转文档的开源插件，做成html页面转文件的微服务集成Web应用，预计包含：

1. 网页转PDF
2. 网页转图片
3. 网页转TEXT
4. 网页转WORD
5. 网页转markdown
6. 网页转excel
7. 网页转...

# 目前支持

![wkhtmltopdf演示图片](docs/images/html2file-pdf-image.gif)

1.网页转PDF（[wkhtml2pdf插件](https://wkhtmltopdf.org)

    例如：http://localhost:7800/html2pdf?pageUrl=https://wkhtmltopdf.org

2.网页转图片（[wkhtml2pdf插件](https://wkhtmltopdf.org)）

    例如：http://localhost:7800/html2image?pageUrl=https://wkhtmltopdf.org&fileExt=[可为空|默认 .png]

![jHTML2Md演示图片](docs/images/html2file-markdown.gif)

3.网页转Markdown（参用[jHTML2Md](https://github.com/pnikosis/jHTML2Md)）

    例如：http://localhost:7800/html2markdown?pageUrl=http://jsoup.org

![html2word演示图片](docs/images/html2file-word.gif)

4.网页转WORD（参用[Apache POI](http://poi.apache.org)）

    例如：http://localhost:7800/html2word?pageUrl=http://poi.apache.org
![html2word演示图片](docs/images/html2file-excel.gif)

5.网页转Excel（参用[table-to-xls](https://gitee.com/chyxion/table-to-xls)）

    例如：http://localhost:7800/html2excel?pageUrl=http://www.jjwxc.net/bookbase_slave.php?booktype=free

# API 服务

1. 服务入口：```/html2file```

2. 请求方式：```post```

3. 服务入参：
    ```java
    {
        "pageUrl":"https://wkhtmltopdf.org",
        "fileType":"2",
        "fileExt":""
        "pageHtmlContent":""
    }
    ```
    - ```pageUrl```：目标链接，带http的链接，无登陆权限验证
    - ```fileType```：文件类型，1-img,2-pdf,3-markdown,4-word,5-excel
    - ```fileExt```：文件扩展名，图片转化可以转化为不同后缀格式的图片
    - ```pageHtmlContent```：目标页面Html内容，当不能提供pageUrl时，将html内容传入也可以转化，其中引用的css和js需要为带http的路径，不能为相对路径

4. 服务出参：
    ```java
    {
        "status"：1,
        "result"："/output/20180417/pdf/9c3fd3018bb041429bba702cd127be9e.pdf",
        "errorMsg"：""
    }
    ```
    - ```status```：状态，1-成功,-1-失败
    - ```result```：结果，这里为转化后的文件路径
    - ```errorMsg```：错误信息

# 服务部署

服务开箱即用，Maven已经配好发布过程，install后将 ```dist``` 内的文件夹复制到服务器，将进入到 ```bin/``` 文件夹下面

1. linux系统

```
chmod +x html2file.sh  #授权脚本
sh html2file.sh start  #启动服务
sh html2file.sh stop   #停止服务
sh html2file.sh reload #重启服务
sh html2file.sh status #状态查看
sh html2file.sh log    #日志查看
```
中文乱码或空白的话，将 ```resources/font/simsun.ttc``` 拷贝到linux服务器 ```/usr/share/fonts/``` 目录下

2. windows系统

直接双击 ```html2file.bat``` 文件即可，也可以将该文件注册成服务，在服务管理里面启动。

# Star History

[![star-history-20231110](https://raw.githubusercontent.com/petterobam/picture-bucket/main/vs-code/upload/imgsstar-history-20231110.png)](https://star-history.com/#petterobam/html2file&Date)



