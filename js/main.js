/* ── aiClaw main.js ── */

/* ── i18n ── */
var T = {
  en: {
    nav_features:"Features", nav_how:"How it works", nav_llm:"LLM", nav_teams:"Teams", nav_oss:"Open Source", nav_pricing:"Pricing", nav_contact:"Contacts", nav_cta:"Get started", nav_cabinet:"Account",
    hero_badge:"Runs 24/7 on your server",
    hero_title:"AI Agent<br><span class=\"accent\">of a new generation</span><br>for your business",
    hero_desc:"Personal assistant in Telegram and Max. Any LLM \u2014 Claude, GPT, Gemini, GigaChat. Long-term memory, automation, knowledge base. Runs on your server.",
    hero_cta:"Get in touch", hero_more:"Learn more",
    stat_llm:"LLM supported", stat_msg:"Messengers", stat_uptime:"Agent uptime", stat_server:"Your infra",
    feat_label:"Features", feat_title:"Everything you need \u2014 built in",
    feat_subtitle:"aiClaw is not just a chatbot. It\u2019s a full AI agent with memory, tools and integrations.",
    f1_title:"Long-term memory", f1_desc:"Two-level memory: SQLite for dialog history and pgvector for semantic fact search. The agent remembers everything important.",
    f2_title:"Hot LLM switching", f2_desc:"Switch model mid-chat without restart. Claude, GPT-4o, Gemini, GigaChat and local models.",
    f3_title:"Task scheduler", f3_desc:"Built-in cron scheduler. Reminders, recurring reports, automated digests \u2014 all configured by voice.",
    f4_title:"Knowledge base (Vault)", f4_desc:"Structured wiki of your projects. The agent auto-logs decisions and updates the base. Always in context.",
    f5_title:"Integrations", f5_desc:"Cloudflare, SWeb, Hestia, n8n, Perplexity, Playwright. Manage infrastructure directly from Telegram.",
    f6_title:"Image generation", f6_desc:"Flux Dev, Kontext, Schnell, Ultra via fal.ai. Create visuals with one command right in the messenger.",
    f7_title:"Privacy & security", f7_desc:"The agent runs only on your server. No external storage, no SaaS. Your data stays with you.",
    f8_title:"Extensibility", f8_desc:"BotModule plugin architecture. Any custom commands and integrations added without touching the core.",
    f9_title:"Group chats", f9_desc:"Full support for Telegram groups and topics. Flexible session isolation: shared, per-user, per-topic.",
    how_label:"Architecture", how_title:"How it works",
    how_subtitle:"Simple and reliable chain: from a messenger message to the result.",
    how1_title:"Messenger", how1_desc:"Telegram or Max. Text, voice, documents, photos \u2014 the agent understands everything.",
    how2_desc:"Memory, scheduler, tools. Context from the knowledge base loads automatically.",
    how3_title:"LLM of choice", how3_desc:"Claude, GPT, Gemini, GigaChat or any local model. Switch when needed.",
    how4_title:"Result", how4_desc:"Answer, action, reminder. The agent remembers decisions and learns from your context.",
    llm_label:"Compatibility", llm_title:"Any LLM.<br>Any messenger.",
    llm_desc:"Don\u2019t lock in to one provider. Connect the latest models as they release. Full support for OpenAI-compatible API.",
    llm_msg_label:"Messengers",
    team_label:"For teams", team_title:"One agent.<br>The whole team.",
    team_desc:"aiClaw works in corporate Telegram and Max chats. Choose the mode for your scenario \u2014 from a shared duty assistant to a personal assistant for every employee.",
    team_note:"The mode is set in config and can be changed without restart. Different rules can be set for different groups \u2014 e.g. passive mode in a work chat, personal in DMs with the manager.",
    mode1_tag:"shared", mode1_title:"Shared chat", mode1_sub:"One dialog for the whole group",
    mode1_f1:"All participants\u2019 messages go into one shared session",
    mode1_f2:"The agent sees the full chat context and knows who wrote what",
    mode1_f3:"Good for duty assistant, digests, briefings",
    mode1_f4:"Shared memory \u2014 facts from conversations available to all",
    mode2_tag:"per_user", mode2_title:"Personal", mode2_sub:"Separate dialog for each",
    mode2_f1:"Each group member gets their own isolated session",
    mode2_f2:"The agent remembers each employee\u2019s history and context separately",
    mode2_f3:"Privacy: others\u2019 messages don\u2019t get into your context",
    mode2_f4:"Ideal for teams where everyone has their own tasks and projects",
    mode3_tag:"mention_only", mode3_title:"Passive", mode3_sub:"Responds only when addressed",
    mode3_f1:"Stays silent in the background \u2014 doesn\u2019t react to regular messages",
    mode3_f2:"Activates on @mention or a keyword",
    mode3_f3:"Doesn\u2019t interrupt team conversation, but always at hand",
    mode3_f4:"Optimal for active work chats",
    pricing_label:"Pricing", pricing_title:"Simple, transparent pricing",
    pricing_sub:"One-time setup. Then only your infrastructure costs \u2014 no subscriptions from us.",
    pricing_onetime:"Setup", pricing_onetime_note:"one-time payment",
    pricing_monthly:"Ongoing costs", pricing_monthly_note:"monthly",
    plan1_tag:"Individual", plan1_title:"Individual", plan1_desc:"For solo use or a small team",
    plan1_f1:"Deploy on VDS", plan1_f2:"Telegram or Max setup", plan1_f3:"LLM of your choice",
    plan1_f4:"Memory & knowledge base", plan1_f5:"1 month included support",
    plan2_tag:"Corporate", plan2_title:"Corporate", plan2_desc:"For teams and enterprises",
    plan2_f1:"Everything in Individual", plan2_f2:"Multiple messengers & group chats",
    plan2_f3:"CRM & n8n integrations", plan2_f4:"Team training & documentation",
    plan2_f5:"3 months included support", plan2_f6:"Custom modules on request",
    plan3_title:"Server support", plan3_desc:"Monitoring, updates, backups",
    plan3_f1:"24/7 uptime monitoring", plan3_f2:"Agent updates (new releases)",
    plan3_f3:"Security patches", plan3_f4:"Monthly status report",
    plan4_title:"LLM model", plan4_desc:"Paid directly to API provider",
    plan4_f1:"Billed by provider (Anthropic, OpenAI\u2026)", plan4_f2:"Claude, GPT, Gemini, GigaChat",
    plan4_f3:"Local models (Ollama) \u2014 free", plan4_f4:"Avg. cost for a small team",
    price_from:"from", plan_mo:"mo",
    plan_cta:"Get started", plan_enterprise_cta:"Contact us",
    pricing_note:"Final cost depends on scope and integrations. Contact us for a custom quote.",
    pricing_btn:"Request setup",
    oss_label:"Open Source", oss_title:"We build tools.<br>We share with the community.",
    oss_desc:"In building aiClaw we create useful tools and publish them open source. Two projects are already on GitHub.",
    oss_btn:"Open projects",
    ar_desc:"Agentic loop for any OpenAI-compatible LLM. 15 tools, MCP, memory.",
    gc_desc:"Fork of agent-runner for GigaChat (Sber). OAuth, compatibility adapters, free tier.",
    rp_desc:"Record and replay macros. Browser action automation without code.",
    contact_label:"Contacts", contact_title:"Tell us about your tasks",
    contact_desc:"We\u2019ll discuss how aiClaw can help your business. Answer any questions about integration and setup.",
    contact_email:"Email", contact_tg:"Telegram",
    form_name:"Your name", form_name_ph:"How should we address you?",
    form_contact:"Telegram or email", form_contact_ph:"@username or email@domain.com",
    form_msg:"Task / question", form_msg_ph:"Describe what you want to automate or what problems to solve...",
    form_btn:"Send request",
    footer_tagline:"AI agent on your server",
    footer_copy:"2026 aiClaw \u00b7 Product by"
  },
  ru: {
    nav_features:"Возможности", nav_how:"Как работает", nav_llm:"LLM", nav_teams:"Команды", nav_oss:"Open Source", nav_pricing:"Цены", nav_contact:"Контакты", nav_cta:"Подключиться", nav_cabinet:"Личный кабинет",
    hero_badge:"\u0420\u0430\u0431\u043e\u0442\u0430\u0435\u0442 24/7 \u043d\u0430 \u0432\u0430\u0448\u0435\u043c \u0441\u0435\u0440\u0432\u0435\u0440\u0435",
    hero_title:"AI-\u0430\u0433\u0435\u043d\u0442<br><span class=\"accent\">\u043d\u043e\u0432\u043e\u0433\u043e \u043f\u043e\u043a\u043e\u043b\u0435\u043d\u0438\u044f</span><br>\u0434\u043b\u044f \u0432\u0430\u0448\u0435\u0433\u043e \u0431\u0438\u0437\u043d\u0435\u0441\u0430",
    hero_desc:"\u041f\u0435\u0440\u0441\u043e\u043d\u0430\u043b\u044c\u043d\u044b\u0439 \u0430\u0441\u0441\u0438\u0441\u0442\u0435\u043d\u0442 \u0432 Telegram \u0438 Max. \u041b\u044e\u0431\u0430\u044f LLM \u2014 Claude, GPT, Gemini, GigaChat. \u0414\u043e\u043b\u0433\u043e\u0441\u0440\u043e\u0447\u043d\u0430\u044f \u043f\u0430\u043c\u044f\u0442\u044c, \u0430\u0432\u0442\u043e\u043c\u0430\u0442\u0438\u0437\u0430\u0446\u0438\u044f, \u0431\u0430\u0437\u0430 \u0437\u043d\u0430\u043d\u0438\u0439. \u0416\u0438\u0432\u0451\u0442 \u043d\u0430 \u0432\u0430\u0448\u0435\u043c \u0441\u0435\u0440\u0432\u0435\u0440\u0435.",
    hero_cta:"\u041e\u0441\u0442\u0430\u0432\u0438\u0442\u044c \u0437\u0430\u044f\u0432\u043a\u0443", hero_more:"\u0423\u0437\u043d\u0430\u0442\u044c \u0431\u043e\u043b\u044c\u0448\u0435",
    stat_llm:"\u041f\u043e\u0434\u0434\u0435\u0440\u0436\u0430\u043d\u044b\u0445 LLM", stat_msg:"\u041c\u0435\u0441\u0441\u0435\u043d\u0434\u0436\u0435\u0440\u0430", stat_uptime:"\u0420\u0430\u0431\u043e\u0442\u0430 \u0430\u0433\u0435\u043d\u0442\u0430", stat_server:"\u0412\u0430\u0448\u0430 \u0438\u043d\u0444\u0440\u0430\u0441\u0442\u0440\u0443\u043a\u0442\u0443\u0440\u0430",
    feat_label:"\u0412\u043e\u0437\u043c\u043e\u0436\u043d\u043e\u0441\u0442\u0438", feat_title:"\u0412\u0441\u0451 \u0447\u0442\u043e \u043d\u0443\u0436\u043d\u043e \u2014 \u0443\u0436\u0435 \u0432\u043d\u0443\u0442\u0440\u0438",
    feat_subtitle:"aiClaw \u2014 \u044d\u0442\u043e \u043d\u0435 \u043f\u0440\u043e\u0441\u0442\u043e \u0447\u0430\u0442-\u0431\u043e\u0442. \u042d\u0442\u043e \u043f\u043e\u043b\u043d\u043e\u0446\u0435\u043d\u043d\u044b\u0439 AI-\u0430\u0433\u0435\u043d\u0442 \u0441 \u043f\u0430\u043c\u044f\u0442\u044c\u044e, \u0438\u043d\u0441\u0442\u0440\u0443\u043c\u0435\u043d\u0442\u0430\u043c\u0438 \u0438 \u0438\u043d\u0442\u0435\u0433\u0440\u0430\u0446\u0438\u044f\u043c\u0438.",
    f1_title:"\u0414\u043e\u043b\u0433\u043e\u0441\u0440\u043e\u0447\u043d\u0430\u044f \u043f\u0430\u043c\u044f\u0442\u044c", f1_desc:"\u0414\u0432\u0430 \u0443\u0440\u043e\u0432\u043d\u044f \u043f\u0430\u043c\u044f\u0442\u0438: SQLite \u0434\u043b\u044f \u0438\u0441\u0442\u043e\u0440\u0438\u0438 \u0434\u0438\u0430\u043b\u043e\u0433\u043e\u0432 \u0438 pgvector \u0434\u043b\u044f \u0441\u0435\u043c\u0430\u043d\u0442\u0438\u0447\u0435\u0441\u043a\u043e\u0433\u043e \u043f\u043e\u0438\u0441\u043a\u0430 \u0444\u0430\u043a\u0442\u043e\u0432. \u0410\u0433\u0435\u043d\u0442 \u043f\u043e\u043c\u043d\u0438\u0442 \u0432\u0441\u0451 \u0432\u0430\u0436\u043d\u043e\u0435.",
    f2_title:"\u0413\u043e\u0440\u044f\u0447\u0435\u0435 \u043f\u0435\u0440\u0435\u043a\u043b\u044e\u0447\u0435\u043d\u0438\u0435 LLM", f2_desc:"\u041c\u0435\u043d\u044f\u0439\u0442\u0435 \u043c\u043e\u0434\u0435\u043b\u044c \u043f\u0440\u044f\u043c\u043e \u0432 \u0447\u0430\u0442\u0435 \u0431\u0435\u0437 \u043f\u0435\u0440\u0435\u0437\u0430\u043f\u0443\u0441\u043a\u0430. Claude, GPT-4o, Gemini, GigaChat \u0438 \u043b\u043e\u043a\u0430\u043b\u044c\u043d\u044b\u0435 \u043c\u043e\u0434\u0435\u043b\u0438.",
    f3_title:"\u041f\u043b\u0430\u043d\u0438\u0440\u043e\u0432\u0449\u0438\u043a \u0437\u0430\u0434\u0430\u0447", f3_desc:"\u0412\u0441\u0442\u0440\u043e\u0435\u043d\u043d\u044b\u0439 cron-\u0448\u0435\u0434\u0443\u043b\u0435\u0440. \u041d\u0430\u043f\u043e\u043c\u0438\u043d\u0430\u043d\u0438\u044f, \u0440\u0435\u0433\u0443\u043b\u044f\u0440\u043d\u044b\u0435 \u043e\u0442\u0447\u0451\u0442\u044b, \u0430\u0432\u0442\u043e\u043c\u0430\u0442\u0438\u0447\u0435\u0441\u043a\u0438\u0435 \u0434\u0430\u0439\u0434\u0436\u0435\u0441\u0442\u044b \u2014 \u0432\u0441\u0451 \u043d\u0430\u0441\u0442\u0440\u0430\u0438\u0432\u0430\u0435\u0442\u0441\u044f \u0433\u043e\u043b\u043e\u0441\u043e\u043c.",
    f4_title:"\u0411\u0430\u0437\u0430 \u0437\u043d\u0430\u043d\u0438\u0439 (Vault)", f4_desc:"\u0421\u0442\u0440\u0443\u043a\u0442\u0443\u0440\u0438\u0440\u043e\u0432\u0430\u043d\u043d\u0430\u044f wiki \u0432\u0430\u0448\u0438\u0445 \u043f\u0440\u043e\u0435\u043a\u0442\u043e\u0432. \u0410\u0433\u0435\u043d\u0442 \u0430\u0432\u0442\u043e\u043c\u0430\u0442\u0438\u0447\u0435\u0441\u043a\u0438 \u043b\u043e\u0433\u0438\u0440\u0443\u0435\u0442 \u0440\u0435\u0448\u0435\u043d\u0438\u044f \u0438 \u043e\u0431\u043d\u043e\u0432\u043b\u044f\u0435\u0442 \u0431\u0430\u0437\u0443. \u0412\u0441\u0435\u0433\u0434\u0430 \u0432 \u043a\u043e\u043d\u0442\u0435\u043a\u0441\u0442\u0435.",
    f5_title:"\u0418\u043d\u0442\u0435\u0433\u0440\u0430\u0446\u0438\u0438", f5_desc:"Cloudflare, SWeb, Hestia, n8n, Perplexity, Playwright. \u0423\u043f\u0440\u0430\u0432\u043b\u044f\u0439\u0442\u0435 \u0438\u043d\u0444\u0440\u0430\u0441\u0442\u0440\u0443\u043a\u0442\u0443\u0440\u043e\u0439 \u043f\u0440\u044f\u043c\u043e \u0438\u0437 Telegram.",
    f6_title:"\u0413\u0435\u043d\u0435\u0440\u0430\u0446\u0438\u044f \u0438\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u0439", f6_desc:"Flux Dev, Kontext, Schnell, Ultra \u0447\u0435\u0440\u0435\u0437 fal.ai. \u0421\u043e\u0437\u0434\u0430\u0432\u0430\u0439\u0442\u0435 \u0432\u0438\u0437\u0443\u0430\u043b\u044b \u043e\u0434\u043d\u043e\u0439 \u043a\u043e\u043c\u0430\u043d\u0434\u043e\u0439 \u043f\u0440\u044f\u043c\u043e \u0432 \u043c\u0435\u0441\u0441\u0435\u043d\u0434\u0436\u0435\u0440\u0435.",
    f7_title:"\u041f\u0440\u0438\u0432\u0430\u0442\u043d\u043e\u0441\u0442\u044c \u0438 \u0431\u0435\u0437\u043e\u043f\u0430\u0441\u043d\u043e\u0441\u0442\u044c", f7_desc:"\u0410\u0433\u0435\u043d\u0442 \u0440\u0430\u0431\u043e\u0442\u0430\u0435\u0442 \u0442\u043e\u043b\u044c\u043a\u043e \u043d\u0430 \u0432\u0430\u0448\u0435\u043c \u0441\u0435\u0440\u0432\u0435\u0440\u0435. \u041d\u0438\u043a\u0430\u043a\u0438\u0445 \u0432\u043d\u0435\u0448\u043d\u0438\u0445 \u0445\u0440\u0430\u043d\u0438\u043b\u0438\u0449, \u043d\u0438\u043a\u0430\u043a\u043e\u0433\u043e SaaS. \u0412\u0430\u0448\u0438 \u0434\u0430\u043d\u043d\u044b\u0435 \u2014 \u0442\u043e\u043b\u044c\u043a\u043e \u0443 \u0432\u0430\u0441.",
    f8_title:"\u0420\u0430\u0441\u0448\u0438\u0440\u044f\u0435\u043c\u043e\u0441\u0442\u044c", f8_desc:"\u041f\u043b\u0430\u0433\u0438\u043d\u043d\u0430\u044f \u0430\u0440\u0445\u0438\u0442\u0435\u043a\u0442\u0443\u0440\u0430 BotModule. \u041b\u044e\u0431\u044b\u0435 \u043a\u0430\u0441\u0442\u043e\u043c\u043d\u044b\u0435 \u043a\u043e\u043c\u0430\u043d\u0434\u044b \u0438 \u0438\u043d\u0442\u0435\u0433\u0440\u0430\u0446\u0438\u0438 \u0434\u043e\u0431\u0430\u0432\u043b\u044f\u044e\u0442\u0441\u044f \u0431\u0435\u0437 \u0438\u0437\u043c\u0435\u043d\u0435\u043d\u0438\u044f \u044f\u0434\u0440\u0430.",
    f9_title:"\u0413\u0440\u0443\u043f\u043f\u043e\u0432\u044b\u0435 \u0447\u0430\u0442\u044b", f9_desc:"\u041f\u043e\u043b\u043d\u0430\u044f \u043f\u043e\u0434\u0434\u0435\u0440\u0436\u043a\u0430 \u0433\u0440\u0443\u043f\u043f \u0438 \u0442\u043e\u043f\u0438\u043a\u043e\u0432 Telegram. \u0413\u0438\u0431\u043a\u0430\u044f \u0438\u0437\u043e\u043b\u044f\u0446\u0438\u044f \u0441\u0435\u0441\u0441\u0438\u0439: \u043e\u0431\u0449\u0430\u044f, per-user, per-topic.",
    how_label:"\u0410\u0440\u0445\u0438\u0442\u0435\u043a\u0442\u0443\u0440\u0430", how_title:"\u041a\u0430\u043a \u044d\u0442\u043e \u0440\u0430\u0431\u043e\u0442\u0430\u0435\u0442",
    how_subtitle:"\u041f\u0440\u043e\u0441\u0442\u0430\u044f \u0438 \u043d\u0430\u0434\u0451\u0436\u043d\u0430\u044f \u0446\u0435\u043f\u043e\u0447\u043a\u0430: \u043e\u0442 \u0441\u043e\u043e\u0431\u0449\u0435\u043d\u0438\u044f \u0432 \u043c\u0435\u0441\u0441\u0435\u043d\u0434\u0436\u0435\u0440\u0435 \u0434\u043e \u0440\u0435\u0437\u0443\u043b\u044c\u0442\u0430\u0442\u0430.",
    how1_title:"\u041c\u0435\u0441\u0441\u0435\u043d\u0434\u0436\u0435\u0440", how1_desc:"Telegram \u0438\u043b\u0438 Max. \u0422\u0435\u043a\u0441\u0442, \u0433\u043e\u043b\u043e\u0441, \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442\u044b, \u0444\u043e\u0442\u043e \u2014 \u0430\u0433\u0435\u043d\u0442 \u043f\u043e\u043d\u0438\u043c\u0430\u0435\u0442 \u0432\u0441\u0451.",
    how2_desc:"\u041f\u0430\u043c\u044f\u0442\u044c, \u0448\u0435\u0434\u0443\u043b\u0435\u0440, \u0438\u043d\u0441\u0442\u0440\u0443\u043c\u0435\u043d\u0442\u044b. \u041a\u043e\u043d\u0442\u0435\u043a\u0441\u0442 \u0438\u0437 \u0431\u0430\u0437\u044b \u0437\u043d\u0430\u043d\u0438\u0439 \u043f\u043e\u0434\u0433\u0440\u0443\u0436\u0430\u0435\u0442\u0441\u044f \u0430\u0432\u0442\u043e\u043c\u0430\u0442\u0438\u0447\u0435\u0441\u043a\u0438.",
    how3_title:"LLM \u043f\u043e \u0432\u044b\u0431\u043e\u0440\u0443", how3_desc:"Claude, GPT, Gemini, GigaChat \u0438\u043b\u0438 \u043b\u044e\u0431\u0430\u044f \u043b\u043e\u043a\u0430\u043b\u044c\u043d\u0430\u044f \u043c\u043e\u0434\u0435\u043b\u044c. \u041f\u0435\u0440\u0435\u043a\u043b\u044e\u0447\u0430\u0435\u0442\u0435 \u043a\u043e\u0433\u0434\u0430 \u043d\u0443\u0436\u043d\u043e.",
    how4_title:"\u0420\u0435\u0437\u0443\u043b\u044c\u0442\u0430\u0442", how4_desc:"\u041e\u0442\u0432\u0435\u0442, \u0434\u0435\u0439\u0441\u0442\u0432\u0438\u0435, \u043d\u0430\u043f\u043e\u043c\u0438\u043d\u0430\u043d\u0438\u0435. \u0410\u0433\u0435\u043d\u0442 \u0437\u0430\u043f\u043e\u043c\u0438\u043d\u0430\u0435\u0442 \u0440\u0435\u0448\u0435\u043d\u0438\u044f \u0438 \u0443\u0447\u0438\u0442\u0441\u044f \u043d\u0430 \u0432\u0430\u0448\u0435\u043c \u043a\u043e\u043d\u0442\u0435\u043a\u0441\u0442\u0435.",
    llm_label:"\u0421\u043e\u0432\u043c\u0435\u0441\u0442\u0438\u043c\u043e\u0441\u0442\u044c", llm_title:"\u041b\u044e\u0431\u043e\u0439 LLM.<br>\u041b\u044e\u0431\u043e\u0439 \u043c\u0435\u0441\u0441\u0435\u043d\u0434\u0436\u0435\u0440.",
    llm_desc:"\u041d\u0435 \u043f\u0440\u0438\u0432\u044f\u0437\u044b\u0432\u0430\u0439\u0442\u0435\u0441\u044c \u043a \u043e\u0434\u043d\u043e\u043c\u0443 \u043f\u0440\u043e\u0432\u0430\u0439\u0434\u0435\u0440\u0443. \u041f\u043e\u0434\u043a\u043b\u044e\u0447\u0430\u0439\u0442\u0435 \u0441\u0430\u043c\u044b\u0435 \u0430\u043a\u0442\u0443\u0430\u043b\u044c\u043d\u044b\u0435 \u043c\u043e\u0434\u0435\u043b\u0438 \u043f\u043e \u043c\u0435\u0440\u0435 \u0438\u0445 \u0432\u044b\u0445\u043e\u0434\u0430. \u041f\u043e\u043b\u043d\u0430\u044f \u043f\u043e\u0434\u0434\u0435\u0440\u0436\u043a\u0430 OpenAI-\u0441\u043e\u0432\u043c\u0435\u0441\u0442\u0438\u043c\u043e\u0433\u043e API.",
    llm_msg_label:"\u041c\u0435\u0441\u0441\u0435\u043d\u0434\u0436\u0435\u0440\u044b",
    team_label:"\u0414\u043b\u044f \u043a\u043e\u043c\u0430\u043d\u0434", team_title:"\u041e\u0434\u0438\u043d \u0430\u0433\u0435\u043d\u0442.<br>\u0412\u0441\u044f \u043a\u043e\u043c\u0430\u043d\u0434\u0430.",
    team_desc:"aiClaw \u0440\u0430\u0431\u043e\u0442\u0430\u0435\u0442 \u0432 \u043a\u043e\u0440\u043f\u043e\u0440\u0430\u0442\u0438\u0432\u043d\u044b\u0445 \u0447\u0430\u0442\u0430\u0445 Telegram \u0438 Max. \u0412\u044b\u0431\u0435\u0440\u0438\u0442\u0435 \u0440\u0435\u0436\u0438\u043c \u043f\u043e\u0434 \u0432\u0430\u0448 \u0441\u0446\u0435\u043d\u0430\u0440\u0438\u0439 \u2014 \u043e\u0442 \u043e\u0431\u0449\u0435\u0433\u043e \u0434\u0435\u0436\u0443\u0440\u043d\u043e\u0433\u043e \u0430\u0441\u0441\u0438\u0441\u0442\u0435\u043d\u0442\u0430 \u0434\u043e \u043f\u0435\u0440\u0441\u043e\u043d\u0430\u043b\u044c\u043d\u043e\u0433\u043e \u043f\u043e\u043c\u043e\u0449\u043d\u0438\u043a\u0430 \u043a\u0430\u0436\u0434\u043e\u0433\u043e \u0441\u043e\u0442\u0440\u0443\u0434\u043d\u0438\u043a\u0430.",
    team_note:"\u0420\u0435\u0436\u0438\u043c \u0440\u0430\u0431\u043e\u0442\u044b \u0437\u0430\u0434\u0430\u0451\u0442\u0441\u044f \u0432 \u043a\u043e\u043d\u0444\u0438\u0433\u0443\u0440\u0430\u0446\u0438\u0438 \u0438 \u043f\u0440\u0438 \u043d\u0435\u043e\u0431\u0445\u043e\u0434\u0438\u043c\u043e\u0441\u0442\u0438 \u043c\u0435\u043d\u044f\u0435\u0442\u0441\u044f \u0431\u0435\u0437 \u043f\u0435\u0440\u0435\u0437\u0430\u043f\u0443\u0441\u043a\u0430. \u0420\u0430\u0437\u043d\u044b\u0435 \u043f\u0440\u0430\u0432\u0438\u043b\u0430 \u043c\u043e\u0436\u043d\u043e \u043d\u0430\u0441\u0442\u0440\u043e\u0438\u0442\u044c \u0434\u043b\u044f \u0440\u0430\u0437\u043d\u044b\u0445 \u0433\u0440\u0443\u043f\u043f.",
    mode1_tag:"shared", mode1_title:"\u041e\u0431\u0449\u0438\u0439 \u0447\u0430\u0442", mode1_sub:"\u0415\u0434\u0438\u043d\u044b\u0439 \u0434\u0438\u0430\u043b\u043e\u0433 \u043d\u0430 \u0432\u0441\u044e \u0433\u0440\u0443\u043f\u043f\u0443",
    mode1_f1:"\u0412\u0441\u0435 \u0441\u043e\u043e\u0431\u0449\u0435\u043d\u0438\u044f \u0443\u0447\u0430\u0441\u0442\u043d\u0438\u043a\u043e\u0432 \u0441\u043a\u043b\u0430\u0434\u044b\u0432\u0430\u044e\u0442\u0441\u044f \u0432 \u043e\u0434\u043d\u0443 \u043e\u0431\u0449\u0443\u044e \u0441\u0435\u0441\u0441\u0438\u044e",
    mode1_f2:"\u0410\u0433\u0435\u043d\u0442 \u0432\u0438\u0434\u0438\u0442 \u0432\u0435\u0441\u044c \u043a\u043e\u043d\u0442\u0435\u043a\u0441\u0442 \u0447\u0430\u0442\u0430 \u0438 \u0437\u043d\u0430\u0435\u0442 \u043a\u0442\u043e \u0447\u0442\u043e \u043d\u0430\u043f\u0438\u0441\u0430\u043b",
    mode1_f3:"\u041f\u043e\u0434\u0445\u043e\u0434\u0438\u0442 \u0434\u043b\u044f \u0434\u0435\u0436\u0443\u0440\u043d\u043e\u0433\u043e \u0430\u0441\u0441\u0438\u0441\u0442\u0435\u043d\u0442\u0430 \u043a\u043e\u043c\u0430\u043d\u0434\u044b, \u0434\u0430\u0439\u0434\u0436\u0435\u0441\u0442\u043e\u0432, \u0431\u0440\u0438\u0444\u0438\u043d\u0433\u043e\u0432",
    mode1_f4:"\u041e\u0431\u0449\u0430\u044f \u043f\u0430\u043c\u044f\u0442\u044c \u2014 \u0444\u0430\u043a\u0442\u044b \u0438\u0437 \u0440\u0430\u0437\u0433\u043e\u0432\u043e\u0440\u043e\u0432 \u0434\u043e\u0441\u0442\u0443\u043f\u043d\u044b \u0432\u0441\u0435\u043c \u0443\u0447\u0430\u0441\u0442\u043d\u0438\u043a\u0430\u043c",
    mode2_tag:"per_user", mode2_title:"\u041f\u0435\u0440\u0441\u043e\u043d\u0430\u043b\u044c\u043d\u044b\u0439", mode2_sub:"\u041e\u0442\u0434\u0435\u043b\u044c\u043d\u044b\u0439 \u0434\u0438\u0430\u043b\u043e\u0433 \u0441 \u043a\u0430\u0436\u0434\u044b\u043c",
    mode2_f1:"\u041a\u0430\u0436\u0434\u044b\u0439 \u0443\u0447\u0430\u0441\u0442\u043d\u0438\u043a \u0433\u0440\u0443\u043f\u043f\u044b \u043f\u043e\u043b\u0443\u0447\u0430\u0435\u0442 \u0441\u0432\u043e\u044e \u0438\u0437\u043e\u043b\u0438\u0440\u043e\u0432\u0430\u043d\u043d\u0443\u044e \u0441\u0435\u0441\u0441\u0438\u044e",
    mode2_f2:"\u0410\u0433\u0435\u043d\u0442 \u043f\u043e\u043c\u043d\u0438\u0442 \u0438\u0441\u0442\u043e\u0440\u0438\u044e \u0438 \u043a\u043e\u043d\u0442\u0435\u043a\u0441\u0442 \u043a\u0430\u0436\u0434\u043e\u0433\u043e \u0441\u043e\u0442\u0440\u0443\u0434\u043d\u0438\u043a\u0430 \u043e\u0442\u0434\u0435\u043b\u044c\u043d\u043e",
    mode2_f3:"\u041f\u0440\u0438\u0432\u0430\u0442\u043d\u043e\u0441\u0442\u044c: \u0447\u0443\u0436\u0438\u0435 \u0441\u043e\u043e\u0431\u0449\u0435\u043d\u0438\u044f \u043d\u0435 \u043f\u043e\u043f\u0430\u0434\u0430\u044e\u0442 \u0432 \u0432\u0430\u0448 \u043a\u043e\u043d\u0442\u0435\u043a\u0441\u0442",
    mode2_f4:"\u0418\u0434\u0435\u0430\u043b\u044c\u043d\u043e \u0434\u043b\u044f \u043a\u043e\u043c\u0430\u043d\u0434, \u0433\u0434\u0435 \u0443 \u043a\u0430\u0436\u0434\u043e\u0433\u043e \u0441\u0432\u043e\u0438 \u0437\u0430\u0434\u0430\u0447\u0438 \u0438 \u043f\u0440\u043e\u0435\u043a\u0442\u044b",
    mode3_tag:"mention_only", mode3_title:"\u041f\u0430\u0441\u0441\u0438\u0432\u043d\u044b\u0439", mode3_sub:"\u041e\u0442\u0432\u0435\u0447\u0430\u0435\u0442 \u0442\u043e\u043b\u044c\u043a\u043e \u043d\u0430 \u043e\u0431\u0440\u0430\u0449\u0435\u043d\u0438\u044f",
    mode3_f1:"\u041c\u043e\u043b\u0447\u0438\u0442 \u0432 \u0444\u043e\u043d\u0435 \u2014 \u043d\u0435 \u0440\u0435\u0430\u0433\u0438\u0440\u0443\u0435\u0442 \u043d\u0430 \u043e\u0431\u044b\u0447\u043d\u044b\u0435 \u0441\u043e\u043e\u0431\u0449\u0435\u043d\u0438\u044f \u0432 \u0447\u0430\u0442\u0435",
    mode3_f2:"\u0412\u043a\u043b\u044e\u0447\u0430\u0435\u0442\u0441\u044f \u043f\u0440\u0438 \u0443\u043f\u043e\u043c\u0438\u043d\u0430\u043d\u0438\u0438 @\u0431\u043e\u0442 \u0438\u043b\u0438 \u043a\u043b\u044e\u0447\u0435\u0432\u043e\u0433\u043e \u0441\u043b\u043e\u0432\u0430",
    mode3_f3:"\u041d\u0435 \u043c\u0435\u0448\u0430\u0435\u0442 \u0436\u0438\u0432\u043e\u043c\u0443 \u043e\u0431\u0449\u0435\u043d\u0438\u044e \u043a\u043e\u043c\u0430\u043d\u0434\u044b, \u043d\u043e \u0432\u0441\u0435\u0433\u0434\u0430 \u043f\u043e\u0434 \u0440\u0443\u043a\u043e\u0439",
    mode3_f4:"\u041e\u043f\u0442\u0438\u043c\u0430\u043b\u0435\u043d \u0434\u043b\u044f \u0440\u0430\u0431\u043e\u0447\u0438\u0445 \u0447\u0430\u0442\u043e\u0432 \u0441 \u0430\u043a\u0442\u0438\u0432\u043d\u043e\u0439 \u043f\u0435\u0440\u0435\u043f\u0438\u0441\u043a\u043e\u0439",
    pricing_label:"Стоимость", pricing_title:"Прозрачные условия",
    pricing_sub:"Разовая оплата за внедрение. Дальше — только ваши расходы на сервер и модели.",
    pricing_onetime:"Внедрение", pricing_onetime_note:"разовый платёж",
    pricing_monthly:"Текущие расходы", pricing_monthly_note:"ежемесячно",
    plan1_tag:"Индивидуальное", plan1_title:"Индивидуальное", plan1_desc:"Для личного использования или небольшой команды",
    plan1_f1:"Развёртывание на VDS", plan1_f2:"Настройка Telegram или Max", plan1_f3:"LLM по вашему выбору",
    plan1_f4:"Долгосрочная память и база знаний", plan1_f5:"1 месяц поддержки включён",
    plan2_tag:"Корпоративное", plan2_title:"Корпоративное", plan2_desc:"Для команд и предприятий",
    plan2_f1:"Всё из Индивидуального", plan2_f2:"Несколько мессенджеров и групп",
    plan2_f3:"Интеграции CRM и n8n", plan2_f4:"Обучение команды и документация",
    plan2_f5:"3 месяца поддержки включено", plan2_f6:"Кастомные модули под запрос",
    plan3_title:"Поддержка сервера", plan3_desc:"Мониторинг, обновления, резервные копии",
    plan3_f1:"Мониторинг доступности 24/7", plan3_f2:"Обновления агента (новые версии)",
    plan3_f3:"Патчи безопасности", plan3_f4:"Ежемесячный отчёт",
    plan4_title:"LLM модель", plan4_desc:"Оплата напрямую провайдеру API",
    plan4_f1:"Оплата провайдеру (Anthropic, OpenAI\u2026)", plan4_f2:"Claude, GPT, Gemini, GigaChat",
    plan4_f3:"Локальные модели (Ollama) — бесплатно", plan4_f4:"Средний расход для небольшой команды",
    price_from:"от", plan_mo:"мес",
    plan_cta:"Начать", plan_enterprise_cta:"Обсудить",
    pricing_note:"Итоговая стоимость зависит от объёма задач и интеграций. Напишите для расчёта.",
    pricing_btn:"Оставить заявку",
    oss_label:"Open Source", oss_title:"\u0421\u0442\u0440\u043e\u0438\u043c \u0438\u043d\u0441\u0442\u0440\u0443\u043c\u0435\u043d\u0442\u044b.<br>\u0414\u0435\u043b\u0438\u043c\u0441\u044f \u0441 \u0441\u043e\u043e\u0431\u0449\u0435\u0441\u0442\u0432\u043e\u043c.",
    oss_desc:"\u0412 \u043f\u0440\u043e\u0446\u0435\u0441\u0441\u0435 \u0440\u0430\u0437\u0440\u0430\u0431\u043e\u0442\u043a\u0438 aiClaw \u043c\u044b \u0441\u043e\u0437\u0434\u0430\u0451\u043c \u043f\u043e\u043b\u0435\u0437\u043d\u044b\u0435 \u0438\u043d\u0441\u0442\u0440\u0443\u043c\u0435\u043d\u0442\u044b \u0438 \u043f\u0443\u0431\u043b\u0438\u043a\u0443\u0435\u043c \u0438\u0445 \u0441 \u043e\u0442\u043a\u0440\u044b\u0442\u044b\u043c \u043a\u043e\u0434\u043e\u043c. \u0414\u0432\u0430 \u043f\u0440\u043e\u0435\u043a\u0442\u0430 \u0443\u0436\u0435 \u0434\u043e\u0441\u0442\u0443\u043f\u043d\u044b \u043d\u0430 GitHub.",
    oss_btn:"\u041e\u0442\u043a\u0440\u044b\u0442\u044b\u0435 \u043f\u0440\u043e\u0435\u043a\u0442\u044b",
    ar_desc:"\u0410\u0433\u0435\u043d\u0442\u0441\u043a\u0438\u0439 \u0446\u0438\u043a\u043b \u0434\u043b\u044f \u043b\u044e\u0431\u043e\u0433\u043e OpenAI-\u0441\u043e\u0432\u043c\u0435\u0441\u0442\u0438\u043c\u043e\u0433\u043e LLM. 15 \u0438\u043d\u0441\u0442\u0440\u0443\u043c\u0435\u043d\u0442\u043e\u0432, MCP, \u043f\u0430\u043c\u044f\u0442\u044c.",
    gc_desc:"\u0424\u043e\u0440\u043a agent-runner \u043f\u043e\u0434 GigaChat (\u0421\u0431\u0435\u0440). OAuth, \u0430\u0434\u0430\u043f\u0442\u0435\u0440\u044b \u0441\u043e\u0432\u043c\u0435\u0441\u0442\u0438\u043c\u043e\u0441\u0442\u0438, \u0431\u0435\u0441\u043f\u043b\u0430\u0442\u043d\u044b\u0439 \u0442\u0430\u0440\u0438\u0444.",
    rp_desc:"\u0417\u0430\u043f\u0438\u0441\u044c \u0438 \u0432\u043e\u0441\u043f\u0440\u043e\u0438\u0437\u0432\u0435\u0434\u0435\u043d\u0438\u0435 \u043c\u0430\u043a\u0440\u043e\u0441\u043e\u0432. \u0410\u0432\u0442\u043e\u043c\u0430\u0442\u0438\u0437\u0430\u0446\u0438\u044f \u0434\u0435\u0439\u0441\u0442\u0432\u0438\u0439 \u0432 \u0431\u0440\u0430\u0443\u0437\u0435\u0440\u0435 \u0431\u0435\u0437 \u043a\u043e\u0434\u0430.",
    contact_label:"\u041a\u043e\u043d\u0442\u0430\u043a\u0442\u044b", contact_title:"\u0420\u0430\u0441\u0441\u043a\u0430\u0436\u0438\u0442\u0435 \u043e \u0441\u0432\u043e\u0438\u0445 \u0437\u0430\u0434\u0430\u0447\u0430\u0445",
    contact_desc:"\u041e\u0431\u0441\u0443\u0434\u0438\u043c \u043a\u0430\u043a aiClaw \u043c\u043e\u0436\u0435\u0442 \u043f\u043e\u043c\u043e\u0447\u044c \u0432\u0430\u0448\u0435\u043c\u0443 \u0431\u0438\u0437\u043d\u0435\u0441\u0443. \u041e\u0442\u0432\u0435\u0442\u0438\u043c \u043d\u0430 \u043b\u044e\u0431\u044b\u0435 \u0432\u043e\u043f\u0440\u043e\u0441\u044b \u043f\u043e \u0438\u043d\u0442\u0435\u0433\u0440\u0430\u0446\u0438\u0438 \u0438 \u043d\u0430\u0441\u0442\u0440\u043e\u0439\u043a\u0435.",
    contact_email:"Email", contact_tg:"Telegram",
    form_name:"\u0412\u0430\u0448\u0435 \u0438\u043c\u044f", form_name_ph:"\u041a\u0430\u043a \u043a \u0432\u0430\u043c \u043e\u0431\u0440\u0430\u0449\u0430\u0442\u044c\u0441\u044f?",
    form_contact:"Telegram \u0438\u043b\u0438 email", form_contact_ph:"@username \u0438\u043b\u0438 email@domain.ru",
    form_msg:"\u0417\u0430\u0434\u0430\u0447\u0430 / \u0432\u043e\u043f\u0440\u043e\u0441", form_msg_ph:"\u041e\u043f\u0438\u0448\u0438\u0442\u0435 \u0447\u0442\u043e \u0445\u043e\u0442\u0438\u0442\u0435 \u0430\u0432\u0442\u043e\u043c\u0430\u0442\u0438\u0437\u0438\u0440\u043e\u0432\u0430\u0442\u044c \u0438\u043b\u0438 \u043a\u0430\u043a\u0438\u0435 \u0437\u0430\u0434\u0430\u0447\u0438 \u0440\u0435\u0448\u0438\u0442\u044c...",
    form_btn:"\u041e\u0442\u043f\u0440\u0430\u0432\u0438\u0442\u044c \u0437\u0430\u044f\u0432\u043a\u0443",
    footer_tagline:"AI-\u0430\u0433\u0435\u043d\u0442 \u043d\u0430 \u0432\u0430\u0448\u0435\u043c \u0441\u0435\u0440\u0432\u0435\u0440\u0435",
    footer_copy:"2026 aiClaw \u00b7 \u041f\u0440\u043e\u0434\u0443\u043a\u0442"
  }
};

function setLang(lang) {
  document.documentElement.lang = lang;
  localStorage.setItem('aiclaw_lang', lang);
  document.querySelectorAll('[data-lang]').forEach(function(b) {
    b.classList.toggle('active', b.dataset.lang === lang);
  });
  document.querySelectorAll('[data-i18n]').forEach(function(el) {
    var key = el.dataset.i18n;
    if (T[lang][key] !== undefined) el.textContent = T[lang][key];
  });
  document.querySelectorAll('[data-i18n-html]').forEach(function(el) {
    var key = el.dataset.i18nHtml;
    if (T[lang][key] !== undefined) el.innerHTML = T[lang][key];
  });
  document.querySelectorAll('[data-i18n-ph]').forEach(function(el) {
    var key = el.dataset.i18nPh;
    if (T[lang][key] !== undefined) el.placeholder = T[lang][key];
  });
}

/* ── NAV scroll effect ── */
function initNavScroll() {
  var nav = document.querySelector('nav');
  if (!nav) return;
  window.addEventListener('scroll', function() {
    if (window.scrollY > 50) {
      nav.classList.add('scrolled');
    } else {
      nav.classList.remove('scrolled');
    }
  }, { passive: true });
}

/* ── Active nav link on scroll ── */
function initNavHighlight() {
  var sections = document.querySelectorAll('section[id]');
  var navLinks = document.querySelectorAll('.nav-links a');
  window.addEventListener('scroll', function() {
    var current = '';
    sections.forEach(function(s) {
      if (window.scrollY >= s.offsetTop - 120) current = s.id;
    });
    navLinks.forEach(function(a) {
      var href = a.getAttribute('href');
      if (href === '#' + current) {
        a.classList.add('active');
      } else {
        a.classList.remove('active');
        a.style.color = '';
      }
    });
  }, { passive: true });
}

/* ── Parallax hero ── */
function initParallax() {
  var heroBg = document.querySelector('.hero-bg');
  var shapes = document.querySelectorAll('.hero-shape');
  if (!heroBg) return;

  window.addEventListener('scroll', function() {
    var y = window.scrollY;
    heroBg.style.transform = 'translateY(' + (y * 0.4) + 'px)';
    shapes.forEach(function(el, i) {
      var speed = 0.15 + i * 0.1;
      var dir = i % 2 === 0 ? 1 : -1;
      el.style.transform = 'translateY(' + (y * speed * dir) + 'px)';
    });
  }, { passive: true });
}

/* ── Intersection Observer: reveal on scroll ── */
function initReveal() {
  var revealEls = document.querySelectorAll(
    '.feature-card, .mode-card, .flow-step, .llm-chip, .reveal'
  );

  revealEls.forEach(function(el) {
    el.classList.add('reveal');
  });

  if (!('IntersectionObserver' in window)) {
    revealEls.forEach(function(el) { el.classList.add('visible'); });
    return;
  }

  var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

  /* stagger delay for grid children */
  document.querySelectorAll('.features-grid, .modes-grid, .llm-grid, .flow-grid').forEach(function(grid) {
    var children = grid.querySelectorAll('.reveal');
    children.forEach(function(child, idx) {
      child.style.transitionDelay = (idx * 80) + 'ms';
    });
  });

  revealEls.forEach(function(el) { observer.observe(el); });
}

/* ── Counter animation for hero stats ── */
function animateCounter(el, target, suffix, duration) {
  var start = 0;
  var startTime = null;
  var isFloat = target % 1 !== 0;

  function step(timestamp) {
    if (!startTime) startTime = timestamp;
    var progress = Math.min((timestamp - startTime) / duration, 1);
    var eased = 1 - Math.pow(1 - progress, 3); /* ease out cubic */
    var current = eased * target;
    el.textContent = (isFloat ? current.toFixed(1) : Math.floor(current)) + suffix;
    if (progress < 1) requestAnimationFrame(step);
  }
  requestAnimationFrame(step);
}

function initCounters() {
  var stats = document.querySelectorAll('.hero-stat-num');
  if (!stats.length) return;

  if (!('IntersectionObserver' in window)) return;

  var counterObserver = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (!entry.isIntersecting) return;
      var el = entry.target;
      var raw = el.textContent.trim();
      var suffix = raw.replace(/[\d.]/g, '');
      var num = parseFloat(raw);
      if (!isNaN(num)) {
        animateCounter(el, num, suffix, 1500);
      }
      counterObserver.unobserve(el);
    });
  }, { threshold: 0.5 });

  stats.forEach(function(el) { counterObserver.observe(el); });
}

/* ── Form sent notification ── */
function initFormNotification() {
  if (!window.location.search.includes('sent=1')) return;
  var msg = document.createElement('div');
  msg.style.cssText = 'position:fixed;top:80px;right:24px;background:#fff;border:1px solid #2563eb;color:#0a0f2e;padding:16px 24px;border-radius:12px;z-index:999;font-size:.9rem;box-shadow:0 8px 32px rgba(10,15,46,0.12);';
  msg.innerHTML = '<i class="fas fa-check-circle" style="color:#16a34a;margin-right:8px;"></i>Request sent! We\'ll be in touch soon.';
  document.body.appendChild(msg);
  setTimeout(function() { msg.remove(); }, 5000);
}

/* ── Code copy buttons (opensource.html) ── */
function initCodeCopy() {
  document.querySelectorAll('.code-copy').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var block = btn.closest('.code-block');
      if (!block) return;
      var lines = block.querySelectorAll('.code-body [class^="cb-"]');
      var text = Array.from(lines).map(function(l) { return l.textContent; }).join('');
      if (!text) {
        text = block.querySelector('.code-body').textContent;
      }
      navigator.clipboard.writeText(text.trim()).then(function() {
        var orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        setTimeout(function() { btn.innerHTML = orig; }, 2000);
      });
    });
  });
}

/* ── Init ── */
(function() {
  var saved = localStorage.getItem('aiclaw_lang') || 'en';
  setLang(saved);

  initNavScroll();
  initNavHighlight();
  initParallax();
  initReveal();
  initCounters();
  initFormNotification();
  initCodeCopy();
})();
