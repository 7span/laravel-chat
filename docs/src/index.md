---
layout: home

hero:
  name: Laravel Chat
  text: The all-in-one Laravel chat package
  tagline: One-to-one and group messaging with file sharing, message tracking, and secure storage
  image:
    src: /hero.svg
    alt: Laravel Chat Hero
  actions:
    - theme: brand
      text: Get Started
      link: /introduction/why-laravel-chat
    - theme: alt
      text: View on GitHub
      link: https://github.com/7span/laravel-chat

features:
  - icon: 💬
    title: One-to-One Chat
    details: Create private conversations between user.

  - icon: 📁
    title: File Sharing Support
    details: Upload and share images with  storage handling for both local and AWS S3.

  - icon: 🔄
    title: Real-Time Events with Broadcasting
    details: Seamlessly integrated with Laravel Echo and Pusher for instant, real-time communication.

  - icon: 🛡️
    title: End-to-End Encryption
    details: Optional encryption and secure file access provide safe, private communication.

---

<script setup>
import { VPTeamMembers } from 'vitepress/theme'

const members = [
  {
    avatar: 'https://github.com/7span.png',
    name: '7Span',
    title: 'Sponsor',
    links: [
      { icon: 'github', link: 'https://github.com/7span' },
      { icon: 'x', link: 'https://x.com/7SpanHQ' }
    ]
  },
  {
    avatar: 'https://github.com/hemratna.png',
    name: 'Hemratna Bhimani',
    title: 'Creator',
    links: [
      { icon: 'github', link: 'https://github.com/hemratna' },
    ]
  },
  {
    avatar: 'https://github.com/kajal-7span.png',
    name: 'Kajal Pandya',
    title: 'Contributor',
    links: [
      { icon: 'github', link: 'https://github.com/kajal-7span' },
    ]
  },
  {
    avatar: 'https://github.com/harshil-7span.png',
    name: 'Harshil Patanvadiya',
    title: 'Contributor',
    links: [
      { icon: 'github', link: 'https://github.com/harshil-7span' },
    ]
  },
]
</script>

### 🙌 Credits

> Big thanks to everyone who contributed to making **Laravel Chat** a powerful and easy-to-use package for real-time communication in Laravel. 🚀

<VPTeamMembers size="small" :members />
