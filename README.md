![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?logo=javascript&logoColor=%23F7DF1E) ![PHP](https://img.shields.io/badge/php-%23777BB4.svg?logo=php&logoColor=white) ![Bootstrap](https://img.shields.io/badge/bootstrap-%23563D7C.svg?logo=bootstrap&logoColor=white) ![NodeJS](https://img.shields.io/badge/node.js-6DA55F?logo=node.js&logoColor=white) ![Vue.js](https://img.shields.io/badge/vuejs-%2335495e.svg?logo=vuedotjs&logoColor=%234FC08D) ![Webpack](https://img.shields.io/badge/webpack-%238DD6F9.svg?logo=webpack&logoColor=black) ![Next Cloud](https://img.shields.io/badge/Next%20Cloud-0B94DE?logo=nextcloud&logoColor=white)
# Santa Cloud

### Advent calendar App for Nextcloud

- ✅ Create your own content for the doors - e.g. competitions, reciepts, poems...
- ✅ Offer your customers entertainment during the Advent season.
- ✅ With built-in test mode.
- ✅ Easy setup via an XML file.

![https://raw.githubusercontent.com/zomtec2311/santacloud/refs/heads/main/SantaCloud.png](https://raw.githubusercontent.com/zomtec2311/santacloud/refs/heads/main/SantaCloud.png)​

## Usage

- It is recommended to download or install this app directly from the [Nextcloud App store](https://apps.nextcloud.com).
- Alternatively you can download the [last santacloud release](https://github.com/zomtec2311/santacloud/releases) based on this repository.

To get started follow the instructions to fill the advent calendar's doors with your content.

## Instructions
After installation you have to create the `apps/santacloud/data/days.xml` file if it is not created yet, you can copy the `apps/santacloud/data/days_example.xml` into same path and rename the copy from `days_example.xml`to `days.xml`. This is necessary, because on an update, this file would be overwritten.
This file has the following structure:
```
<?xml version="1.0" encoding="UTF-8" ?>
<calendar>
    <days>
        <day>
            <date>2025-12-01</date>
            <title><![CDATA[Here You can set a Title. Take a look at day 2025-12-02 for a full example]]></title>
            <description><![CDATA[
              <b>Here you can insert your wanted content for a door</b><br>
              You can use HTML. Images should be stored into app's data/img/ folder and then use img src="./data/img/your_image"
              like the example from 2025-12-02 <br>
              DO NOT USE http and/or www with domain for the images, because it will result in error
              ]]></description>
        </day>
        .
        .
        .
    </days>
</calendar>
```
Do not remove the opening `![CDATA[` and closing <code>]]</code> because if you'll remove, you can't use HTML in between. Date has to be this format: <code>YYYY-MM-DD</code>. Fill out all title and description tags with your wished content, save the file to <code>apps/santacloud/data/days.xml</code> and enjoy.

You can reach the admin settings for santacloud over the Administration Settings link or with the direct call over `YOUR_NEXTCLOUD/settings/admin/santacloud`.
