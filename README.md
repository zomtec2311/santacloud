![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?logo=javascript&logoColor=%23F7DF1E) ![PHP](https://img.shields.io/badge/php-%23777BB4.svg?logo=php&logoColor=white) ![Bootstrap](https://img.shields.io/badge/bootstrap-%23563D7C.svg?logo=bootstrap&logoColor=white) ![NodeJS](https://img.shields.io/badge/node.js-6DA55F?logo=node.js&logoColor=white) ![Vue.js](https://img.shields.io/badge/vuejs-%2335495e.svg?logo=vuedotjs&logoColor=%234FC08D) ![Webpack](https://img.shields.io/badge/webpack-%238DD6F9.svg?logo=webpack&logoColor=black) ![Next Cloud](https://img.shields.io/badge/Next%20Cloud-0B94DE?logo=nextcloud&logoColor=white) 

![GitHub](https://img.shields.io/github/license/zomtec2311/santacloud)
![GitHub all downloads](https://img.shields.io/github/downloads/zomtec2311/santacloud/total?logo=github) ![GitHub all releases](https://img.shields.io/github/release/zomtec2311/santacloud)
# Santa Cloud

## ✨ About

### Advent calendar app for Nextcloud

- ✅ Create your own content for the doors - e.g. competitions, reciepts, poems...
- ✅ Offer your customers entertainment during the Advent season.
- ✅ With built-in test mode.
- ✅ Easy setup via an XML file.

![https://raw.githubusercontent.com/zomtec2311/santacloud/refs/heads/main/SantaCloud.png](https://raw.githubusercontent.com/zomtec2311/santacloud/refs/heads/main/SantaCloud.png)​

## ⚙️ Usage

- It is recommended to download or install this app directly from the [Nextcloud App store](https://apps.nextcloud.com/apps/santacloud).
- Alternatively you can download the [latest santacloud release](https://github.com/zomtec2311/santacloud/releases) based on this repository.

To get started follow the instructions to fill the advent calendar's doors with your content.

## 🚀 Instructions
After installation `days_example.xml` will be copied to `nextcloud-datadirectory/santacloud/days.xml`.

You can reach the admin settings for santacloud over the Administration Settings link or with the direct call over `YOUR_NEXTCLOUD/settings/admin/santacloud`.

> [!CAUTION]
> Advises about risks or negative outcomes of certain actions.
> 
> DO NOT store images within the apps data or image folder, because they will be deleted on the next app update automatically!!!
>
> Starting with version 1.1.0, a folder named <code>nextcloud-datadirectory/appdata_your-instanceid/santacloud/img</code> will be created to store all images used in the Advent calendar doors. These images will remain unaffected by future app updates and will therefore not be lost.
>
> Example:
>
> An image "imageABC.png" is stored in <code>nextcloud-datadirectory/appdata_your-instanceid/santacloud/img/imageABC.png</code> you can use it in your HTML code this way:
> ```html
> <img src="YOUR-DOMAIN/apps/santacloud/image/imageABC.png" />
>
> <img src="/apps/santacloud/image/imageABC.png" />

## 💡 F.A.Q.

<details>
  <summary><b>How to change background image?</b></summary>

From version 1.1.0 onwards, you can upload your preferred background image via the SantaCloud Administrator settings and set it up as a new background with one click. </details>

<details>
  <summary><b>All of the text is in english?</b></summary>
	Maybe your language files are missing.

  You might want to help translating the app to new languages or report errors in existing translations. So feel free and send me translations.
</details>

<details>
  <summary><b>Very bad translation?</b></summary>
  We used the AI-based Google translator to generate language files. Of course, there were limitations to the translation depending on the quality of the AI. If you'd like to help improve your language file, open an issue and report your suggestion for improvement. Thank you
</details>

## 🤝 How you can support this project

1. **🌟 Star this repository**: This is the easiest way to support SantaCloud and it costs nothing.
2. **⭐ Rate and/or 💬 comment** on SantaCloud in the [ Nextcloud AppStore](https://apps.nextcloud.com/apps/santacloud)
3. **🪲 Report bugs**: Report any bugs you find on the issue tracker.
4. **📖 Translate**: Help translate SantaCloud into your language, if the AI-based Google translator generated language files are poorly translated
5. **📝 Contribute**: Read and file or comment on an issue and ask for guidance or give advice.

## 🌍 How to Contribute Translations (Step-by-Step Guide)

We love contributions! If you notice a missing or incorrect translation, you can easily fix it directly on GitHub without installing any specialized software.

Follow these simple steps to make your changes and send us a **Pull Request (PR)**:

---

### Step 1: Fork the Repository
A **Fork** creates your own copy of this project under your GitHub account, allowing you to make changes safely.

1. Scroll to the very top right of this GitHub page.
2. Click the **Fork** button (located near the *Star* button).
3. Click **Create fork**. You will now be redirected to your own copy of the repository.

---

### Step 2: Locate and Edit the Translation File
1. In your forked repository, navigate to the folder containing the language files (e.g., `l10n/`).
2. Click on the file for your language (for example, `de.json` or `santacloud_de.po`).
3. Click the **Pencil icon** (✏️) in the top-right corner of the file view to open the editor.
4. Edit or add the missing translations carefully:
   * **JSON files:** Keep the quotes `""` and commas `,` intact.
   * **PO files:** Add your translation inside the `msgstr ""` quotes right below the matching `msgid ""`.

---

### Step 3: Commit Your Changes
Once you have made your edits:

1. Click the green **Commit changes...** button at the top right of the editor.
2. Enter a short description of what you changed (e.g., `Fix German translation for plain text hint`).
3. Make sure **Commit directly to the `main` branch** (or `master`) is selected.
4. Click **Commit changes**.

---

### Step 4: Submit Your Pull Request (PR)
Now send your changes back to us so we can review and include them in the official release!

1. Go back to the main page of **your forked repository**.
2. You should see a banner at the top showing your branch is ahead. Click **Contribute**, then click **Open pull request**.
   *(If you don't see the banner, click the **Pull requests** tab, then the green **New pull request** button).*
3. Double-check your changes on the comparison screen.
4. Click **Create pull request**.
5. Add a brief title and description, then click **Create pull request** once more to finalize.

---

🎉 **That's it!** Thank you for helping improve the project. We will review your contribution as soon as possible and merge it into the main repository.


