![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?logo=javascript&logoColor=%23F7DF1E) ![PHP](https://img.shields.io/badge/php-%23777BB4.svg?logo=php&logoColor=white) ![Bootstrap](https://img.shields.io/badge/bootstrap-%23563D7C.svg?logo=bootstrap&logoColor=white) ![NodeJS](https://img.shields.io/badge/node.js-6DA55F?logo=node.js&logoColor=white) ![Vue.js](https://img.shields.io/badge/vuejs-%2335495e.svg?logo=vuedotjs&logoColor=%234FC08D) ![Webpack](https://img.shields.io/badge/webpack-%238DD6F9.svg?logo=webpack&logoColor=black) ![Next Cloud](https://img.shields.io/badge/Next%20Cloud-0B94DE?logo=nextcloud&logoColor=white) 

![GitHub](https://img.shields.io/github/license/zomtec2311/santacloud)
![GitHub all downloads](https://img.shields.io/github/downloads/zomtec2311/santacloud/total?logo=github) ![GitHub all releases](https://img.shields.io/github/release/zomtec2311/santacloud)
# Santa Cloud

### Advent calendar App for Nextcloud

- ✅ Create your own content for the doors - e.g. competitions, reciepts, poems...
- ✅ Offer your customers entertainment during the Advent season.
- ✅ With built-in test mode.
- ✅ Easy setup via an XML file.

![https://raw.githubusercontent.com/zomtec2311/santacloud/refs/heads/main/SantaCloud.png](https://raw.githubusercontent.com/zomtec2311/santacloud/refs/heads/main/SantaCloud.png)​

## Usage

- It is recommended to download or install this app directly from the [Nextcloud App store](https://apps.nextcloud.com/apps/santacloud).
- Alternatively you can download the [latest santacloud release](https://github.com/zomtec2311/santacloud/releases) based on this repository.

To get started follow the instructions to fill the advent calendar's doors with your content.

## Instructions
After installation `days_example.xml` will be copied to `nextcloud-datadirectory/days.xml`.

You can reach the admin settings for santacloud over the Administration Settings link or with the direct call over `YOUR_NEXTCLOUD/settings/admin/santacloud`.

![https://raw.githubusercontent.com/zomtec2311/santacloud/refs/heads/main/santacloud-settings.png](https://raw.githubusercontent.com/zomtec2311/santacloud/refs/heads/main/santacloud-settings.png)

## F.A.Q.

<details>
  <summary><b>How to change background image?</b></summary>

Take an image of your choice jpg-format and save as <code>background.jpg</code> into folder <code>apps/santacloud/img/</code>.

If you want to take another format, you also have to edit <code>css/santacloud-main.css</code> about line 22

```
.cards-wrapper {
background-image: url('../img/background.jpg');
background-size: 100% 100%;
max-width: 800px;
padding: 48px;
}
```

and don't forget to clean up browser cache</details>

<details>
  <summary><b>All of the text is in English?</b></summary>
	Maybe your language files are missing.

  You might want to help translating the app to new languages or report errors in existing translations. So feel free and send me translations.
</details>
