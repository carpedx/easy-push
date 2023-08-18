import { defineConfig } from 'vitepress'
import nav from './nav'
import sidebar from "./sidebar"

export default defineConfig({
    lang: 'zh-CN',
    title: 'Push',
    description: '整合了各大平台厂商的推送消息组件',
    lastUpdated: true,
    head: [
        ['link', { rel: 'icon', href: '/images/icon.png' }]
    ],
    themeConfig: {
        logo: '/images/icon.png',
        nav: nav,
        sidebar: sidebar,
        socialLinks: [
            { icon: 'github', link: 'https://github.com/carpedx/easy-push' },
        ],
        editLink: {
            pattern: 'https://github.com/carpedx/easy-push/edit/main/web/:path',
            text: 'Edit this page on GitHub'
        },
        footer: {
            message: 'Released under the MIT License.',
            copyright: 'Copyright © 2023-present carpedx'
        },
        search: {
            provider: 'algolia',
            options: {
                appId: '',
                apiKey: '',
                indexName: 'carpedx'
            }
        }
    }
})
