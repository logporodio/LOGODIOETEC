# 🍿 CINEKIDS - O Cinema da Garotada

Projeto desenvolvido pela turma de Logística da **ETEC** para criação de uma plataforma de catálogo e streaming de filmes segura e divertida, voltada ao público infantil e juvenil.

Inspirado nas melhores funcionalidades do **Netflix** (layout em carrosséis temáticos, hero banner interativo, modal de detalhes rápidos com sinopse e botão de assistir) e do **IMDb** (avaliações em estrelas, ficha técnica completa, ano, duração, classificação indicativa e críticas dos usuários).

---

## 🎨 Identidade Visual
- **Paleta de Cores:** Fundo e base em **amarelo pastel** com contrastes vibrantes e acolhedores em **azul** e **rosa**.
- **Design:** Responsivo com **Tailwind CSS**, priorizando o conceito **Mobile-First**.
- **Tipografia:** Fredoka e Quicksand para uma leitura divertida e agradável.

---

## 🛡️ Regras de Negócio e Segurança (CORE)
1. **Público Infanto-Juvenil (Sem conteúdo +14):**
   - Curadoria exclusiva de animações, aventuras e clássicos infantis (Disney, Pixar, DreamWorks, Illumination, etc.).
   - Classificações permitidas no sistema: **Livre (L)**, **10 Anos** e **12 Anos**. É bloqueada qualquer inserção de filmes com classificação indicativa +14, +16 ou +18.
2. **Senhas com Hash BCrypt:**
   - Criptografia com algoritmo nativo `PASSWORD_BCRYPT` em todos os cadastros e trocas de senha.
3. **Auditoria e Histórico Completo de Logs:**
   - Registro imutável de todas as ações feitas por qualquer usuário (Logins, Falhas, Cadastros, Inserções de Filmes, Edições, Exclusões, Avaliações, Favoritos e Reproduções).
   - Tela dedicada `historico.php` com filtros por tipo de evento, pesquisa textual e estatísticas em tempo real.
4. **Proteção contra SQL Injection:**
   - Banco de dados MySQL conectado via **PDO**, utilizando exclusivamente *Prepared Statements* com parâmetros vinculados.
5. **Experiência do Usuário (Toasts & Modais):**
   - **TOASTS:** Mensagens amigáveis de sucesso, aviso e erro exibidas em notificações flutuantes animadas.
   - **MODAIS:** Nenhuma caixa de mensagem nativa do navegador (`alert` ou `confirm`) é utilizada. Todas as confirmações de exclusão e avisos são apresentadas em modais customizados estilizados com Tailwind CSS.
6. **Ao Clicar no Filme:**
   - Abre modal interativo com breve descrição, notas, gêneros, elenco e o botão direto para **Assistir**.

---

## 🚀 Estrutura de Arquivos

```
LOGPORODIO/
├── actions/                 # Scripts backend isolados (processamento)
│   ├── avaliar_filme.php    # Avaliação de filmes com estrelas
│   ├── cadastro_action.php  # Registro de usuários (hash bcrypt)
│   ├── excluir_filme.php    # Exclusão de filmes (com auditoria)
│   ├── favoritar_action.php # Alternar filmes favoritos
│   ├── login_action.php     # Autenticação segura de sessões
│   ├── logout_action.php    # Encerramento de sessão
│   ├── perfil_action.php    # Atualização de dados cadastrais
│   └── salvar_filme.php     # Cadastro e edição de filmes (com validação +14)
├── api/                     # Endpoints JSON para interações dinâmicas
│   └── get_filme.php
├── assets/
│   ├── css/custom.css       # Estilos complementares e selos de classificação
│   └── js/app.js            # Modais universais, toasts e carrosséis
├── config/
│   └── bd.php               # Conexão PDO segura com MySQL
├── database/
│   └── schema.sql           # Script de criação do banco e dados iniciais
├── includes/
│   ├── auth.php             # Gerenciamento de sessões e permissões
│   ├── footer.php           # Rodapé padrão
│   ├── header.php           # Navbar responsiva e menu mobile
│   ├── logs.php             # Função central de registro de auditoria
│   ├── modal.php            # Sistema universal de modais (sem popups nativos)
│   └── toast.php            # Sistema de notificações flutuantes
├── admin_filmes.php         # Painel administrativo de gerenciamento de filmes
├── assistir.php             # Player cinematográfico infantil (estilo Netflix)
├── bd.php                   # Ponto de conexão compatível com a raiz
├── cadastro.php             # Tela de cadastro de novos usuários
├── favoritos.php            # Minha lista de filmes favoritos
├── filme.php                # Ficha técnica detalhada (estilo IMDb Kids)
├── historico.php            # Painel de auditoria e trilha de logs
├── index.php                # Página inicial com Hero Banner e carrosséis Netflix
├── login.php                # Tela de login com preenchimento rápido para testes
├── perfil.php               # Perfil do usuário e histórico pessoal
├── pesquisa.php             # Catálogo completo com filtros avançados
└── requisitos.md            # Especificações originais do trabalho
```

---

## ⚡ Como Rodar o Projeto

### 1. Banco de Dados MySQL
Certifique-se de que o MySQL esteja rodando (ex: via XAMPP). Importe o arquivo:
```bash
mysql -u root cinekids < database/schema.sql
```

### 2. Iniciar o Servidor PHP
Na pasta do projeto, execute:
```bash
php -S 127.0.0.1:8000
```
Acesse no seu navegador: **`http://127.0.0.1:8000`**

---

## 🔑 Contas de Demonstração

| Tipo | E-mail | Senha |
|---|---|---|
| **Administrador** | `admin@cinekids.com` | `admin123` |
| **Turma ETEC** | `aluno@etec.sp.gov.br` | `etec123` |
| **Criança / Usuário** | `kid@cinekids.com` | `kid123` |
