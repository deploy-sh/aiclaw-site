# aiClaw — Multi-Agent AI Assistant

Landing page for [aiclaw.korfix.app](https://aiclaw.korfix.app)

## About

aiClaw is a multi-agent AI assistant for Telegram and Max messenger. Supports Claude, GPT, Gemini, GigaChat, Ollama, and OpenRouter. Built with memory (SQLite + pgvector), cron scheduler, vault knowledge base, and binary distribution.

## Files

- `index.html` — Main landing page (EN/RU, i18n)
- `opensource.html` — Open Source projects page (agent-runner, giga-cli, repio.dev)
- `og-image.png` — OG image for social sharing (main)
- `og-image-oss.png` — OG image for OSS page

## Deploy

Hosted on Hestia at `aiclaw.korfix.app`. To deploy:

```bash
cat index.html | ssh u_korfix@138.124.69.245 "cat > /home/u_korfix/web/aiclaw.korfix.app/public_html/index.html"
cat opensource.html | ssh u_korfix@138.124.69.245 "cat > /home/u_korfix/web/aiclaw.korfix.app/public_html/opensource.html"
```

## Open Source

- [agent-runner](https://github.com/deploy-sh/agent-runner) — TypeScript/Node.js agent runner, 15 tools, MIT
- giga-cli — GigaChat adapter (fork of agent-runner, `giga_cli` branch)
- [repio.dev](https://repio.dev) — Macro recording & replay automation
