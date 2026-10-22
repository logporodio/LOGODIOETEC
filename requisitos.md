# Cine Kids

## Objetivos
crie um site de filmes para o publico infanto juvenil, com filmes de animaçao e outros, pegando caracteristicas do site https://www.imdb.com/pt/ so que com filmes mais infantis. faça com o catalogo parecido com o da netflix (https://www.netflix.com/br/), filmes da disney tambem por exemplo. alem de evitar filmes para um publico +14 para cima. pelo que escrevi, crie o site solicitado, ja usando essas informaçoes e evoluindo elas. ao clicar em um filme permita o usuario ver uma breve descriçao do filme e o botao para assistir.

### Stack Tecnlógico
- Backend: PHP estruturado com sessões nativas
- Banco de dados: MySQL (PDO para segurança)
- Frontend: HTML5, PHP, CSS, Tailwind CSS

#### Regras de negócio (CORE)
Tratar senhas de usuários com hash bcript
O sistema deve ter uma página de históricos e manter sempre os logs de qualquer alteração feita por 
qualquer usuário, para auditorias futuras.
A pagina deve ser nas cores amarelo pastel com contrastes azuis e rosa

##### Regras Globais
- Use sempre PDO para conexão e queries no MySQL para evitar SQL Injections
- Mantenha o código limpo e comente apenas logicas complexas.
- Separe os arquivos de forma lógica: um arquivo para conexão com a base (bd.php) e scripts de backend isolados e views em HTML5/PHP, nunca faça o sistema como um monolito, deixe sempre separados todas as regras para facilitar os futuros upgrades.
 - Estilize as telas em Tailwind de forma responsiva priorizando o MobileFrist.
 - Retorne sempre as mensagens de erros de forma claras na interface para o usuário (TOAST)
 - sempre trate as mensagens de caixa de mensagens nativas do navegador em um MODAL
