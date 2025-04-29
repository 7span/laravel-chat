import { defineConfig } from "vitepress";

export default defineConfig({
  title: "Laravel Chat",
  description:
    "A comprehensive Laravel chat package for one-to-one and group chat integration with document sharing capabilities",
  base: "/open-source/laravel-chat/",
  head: [["link", { rel: "icon", href: "/logo.svg" }]],
  srcDir: "src",
  themeConfig: {
    siteTitle: "Laravel Chat",
    logo: "/logo.svg",
    nav: [{ text: "Home", link: "/index.md" }],
    lastUpdated: {
      text: "Updated at",
      formatOptions: {
        dateStyle: "full",
        timeStyle: "medium",
      },
    },
    outline: [2, 3],
    sidebar: [
      {
        text: "Introduction",
        items: [
          { text: "Why Laravel Chat?", link: "/introduction/why-laravel-chat" },
        ],
      },
      {
        text: "Getting Started", 
        items: [
          {
            text: "Installation", link: "/getting-started/installation",
          },
          {
            text: "Configuration", link: "/getting-started/configuration",
          },
        ]
      },
      {
        text: "Usage",
        items: [
          {
            text: "Channel", link: "/usage/channel",
          },
          {
            text: "Message", link: "/usage/message",
          },
          {
            text: "User", link: "/usage/user",
          },
        ],
      },
    ],
    search: {
      provider: "local",
    },
    socialLinks: [
      { icon: "github", link: "https://github.com/7span/laravel-chat" },
    ],
    footer: {
      message: "Released under the MIT License.",
      copyright: "Copyright © 2024 7Span",
    },
  },
});
