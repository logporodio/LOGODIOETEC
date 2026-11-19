-- Banco de Dados para CineKids
CREATE DATABASE IF NOT EXISTS `cinekids` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cinekids`;

-- 1. Tabela de Usuários
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `senha` VARCHAR(255) NOT NULL,
  `tipo` ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario',
  `avatar` VARCHAR(50) DEFAULT 'avatar1.png',
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabela de Filmes
CREATE TABLE IF NOT EXISTS `filmes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `titulo` VARCHAR(200) NOT NULL,
  `titulo_original` VARCHAR(200) NULL,
  `sinopse` TEXT NOT NULL,
  `ano` INT NOT NULL,
  `duracao` VARCHAR(50) NOT NULL,
  `classificacao` VARCHAR(10) NOT NULL DEFAULT 'L',
  `categoria` VARCHAR(100) NOT NULL,
  `generos` VARCHAR(255) NOT NULL,
  `nota_imdb` DECIMAL(3,1) NOT NULL DEFAULT 8.0,
  `poster_url` TEXT NOT NULL,
  `banner_url` TEXT NOT NULL,
  `trailer_url` VARCHAR(255) NOT NULL,
  `diretor` VARCHAR(150) NULL,
  `elenco` TEXT NULL,
  `destaque` TINYINT(1) DEFAULT 0,
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabela de Histórico e Logs de Auditoria
CREATE TABLE IF NOT EXISTS `logs_auditoria` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NULL,
  `usuario_nome` VARCHAR(150) NULL,
  `acao` VARCHAR(50) NOT NULL,
  `detalhes` TEXT NOT NULL,
  `ip` VARCHAR(45) NULL,
  `data_hora` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabela de Avaliações
CREATE TABLE IF NOT EXISTS `avaliacoes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `filme_id` INT NOT NULL,
  `usuario_id` INT NOT NULL,
  `nota` INT NOT NULL CHECK (`nota` BETWEEN 1 AND 5),
  `comentario` TEXT NULL,
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`filme_id`) REFERENCES `filmes`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `uk_filme_usuario_aval` (`filme_id`, `usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabela de Favoritos
CREATE TABLE IF NOT EXISTS `favoritos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `filme_id` INT NOT NULL,
  `usuario_id` INT NOT NULL,
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`filme_id`) REFERENCES `filmes`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `uk_filme_usuario_fav` (`filme_id`, `usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Limpar dados existentes para reinicialização limpa
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `favoritos`;
TRUNCATE TABLE `avaliacoes`;
TRUNCATE TABLE `logs_auditoria`;
TRUNCATE TABLE `filmes`;
TRUNCATE TABLE `usuarios`;
SET FOREIGN_KEY_CHECKS = 1;

-- Inserir Usuários Padrão (senhas com bcrypt)
INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `avatar`) VALUES
(1, 'Administrador CineKids', 'admin@cinekids.com', '$2y$10$d1AHN9Febr/pXOVS3xUUk.RUHU9KvpwqXci0KPACdD6KwW8dNiBIK', 'admin', 'avatar_admin.png'),
(2, 'Turma de Logística ETEC', 'aluno@etec.sp.gov.br', '$2y$10$GxKNMoGXxvifP17zdBx.Y.Nu/irONhOgUxVrvHoQAyu7KP8lIJyR2', 'admin', 'avatar_etec.png'),
(3, 'Pedro Aventureiro', 'kid@cinekids.com', '$2y$10$BvWAVKi.SkcNnuRiHg2GpeMaIsMxPOQWeuneDerrDPcpYpV.R0PXe', 'usuario', 'avatar_kid.png');

-- Inserir Filmes Infantis Iniciais (Classificações L, 10, 12 - sem +14)
INSERT INTO `filmes` (`id`, `titulo`, `titulo_original`, `sinopse`, `ano`, `duracao`, `classificacao`, `categoria`, `generos`, `nota_imdb`, `poster_url`, `banner_url`, `trailer_url`, `diretor`, `elenco`, `destaque`) VALUES
(1, 'Divertida Mente 2', 'Inside Out 2', 'Riley entra na adolescência e a sala de controle das suas emoções passa por uma grande reforma para abrir espaço para novas emoções inesperadas: Ansiedade, Inveja, Tédio e Vergonha!', 2024, '1h 36m', 'L', 'Disney & Pixar', 'Animação, Comédia, Aventura, Família', 7.7, 
'https://image.tmdb.org/t/p/w500/vpnVM9B6NMmQpWeZvzLvDESb2QY.jpg', 
'https://image.tmdb.org/t/p/original/xg270vg9bkHN9Ibz8stWd2syjlO.jpg', 
'L4DrolmDxmw', 'Kelsey Mann', 'Amy Poehler, Maya Hawke, Phyllis Smith, Lewis Black', 1),

(2, 'Moana: Um Mar de Aventuras', 'Moana', 'Uma jovem corajosa parte em uma missão ousada pelo oceano para salvar seu povo, acompanhada pelo poderoso semideus Maui em uma jornada repleta de monstros marinhos e tradições ancestrais.', 2016, '1h 47m', 'L', 'Disney & Pixar', 'Animação, Aventura, Família, Musical', 7.6, 
'https://image.tmdb.org/t/p/w500/v3e0YJd1hZgM5q12Y8s87Z1aU9e.jpg', 
'https://image.tmdb.org/t/p/original/og1F1rZ1uJ9k56tVfP7p2j0H32u.jpg', 
'LKFuXETZUsI', 'Ron Clements, John Musker', 'Auli''i Cravalho, Dwayne Johnson, Rachel House', 1),

(3, 'Viva: A Vida é uma Festa', 'Coco', 'Apesar da proibição da música por gerações de sua família, Miguel sonha em se tornar um grande músico. Desesperado para provar seu talento, ele se vê na deslumbrante e colorida Terra dos Mortos.', 2017, '1h 45m', 'L', 'Disney & Pixar', 'Animação, Aventura, Fantasia, Família', 8.4, 
'https://image.tmdb.org/t/p/w500/eKi8fqWEbWb9OF5eaYoRdGZ6902.jpg', 
'https://image.tmdb.org/t/p/original/askg3SMvhqEl4OL52YuvdtQw40Y.jpg', 
'iFs_4K_6N9A', 'Lee Unkrich, Adrian Molina', 'Anthony Gonzalez, Gael García Bernal, Benjamin Bratt', 1),

(4, 'Super Mario Bros. O Filme', 'The Super Mario Bros. Movie', 'Um encanador chamado Mario viaja por um labirinto subterrâneo com seu irmão Luigi, tentando salvar a princesa capturada e impedir o terrível plano do vilão Bowser.', 2023, '1h 32m', 'L', 'Aventura & Animação', 'Animação, Aventura, Comédia, Família', 7.0, 
'https://image.tmdb.org/t/p/w500/qNBAXBIQlnOThrVvA6mA2B5ggV6.jpg', 
'https://image.tmdb.org/t/p/original/9n2tJBplPbgR2ca05hS5U7wToIS.jpg', 
'c382kGqWjWc', 'Aaron Horvath, Michael Jelenic', 'Chris Pratt, Anya Taylor-Joy, Charlie Day, Jack Black', 0),

(5, 'Como Treinar o Seu Dragão', 'How to Train Your Dragon', 'Um jovem viking que não se encaixa na tradição da sua tribo de caçadores de dragões acaba fazendo amizade com a criatura mais temida de todas: um Fúria da Noite chamado Banguela.', 2010, '1h 38m', '10', 'Aventura & Animação', 'Animação, Aventura, Fantasia, Ação', 8.1, 
'https://image.tmdb.org/t/p/w500/ygGmAO60t8GyqUo9x9e2G1vW72s.jpg', 
'https://image.tmdb.org/t/p/original/1u7kkfPqL7eR4Gk0uYxZ98p1T3e.jpg', 
'1L0T9v_V0t4', 'Dean DeBlois, Chris Sanders', 'Jay Baruchel, Gerard Butler, America Ferrera', 0),

(6, 'Toy Story', 'Toy Story', 'Um boneco caubói de corda se sente profundamente ameaçado e com ciúmes quando um novo astronauta espacial chega como o novo brinquedo favorito do garoto Andy.', 1995, '1h 21m', 'L', 'Clássicos Infantis', 'Animação, Aventura, Comédia, Família', 8.3, 
'https://image.tmdb.org/t/p/w500/uXDfjJbdP4ijW5hWSBrPrlKpxab.jpg', 
'https://image.tmdb.org/t/p/original/lxD5ak7BOxMg98Ce2ILHVvdpTeJ.jpg', 
'wmiIUN-7UPE', 'John Lasseter', 'Tom Hanks, Tim Allen, Don Rickles, Jim Varney', 0),

(7, 'Homem-Aranha: Através do Aranhaverso', 'Spider-Man: Across the Spider-Verse', 'Miles Morales é catapultado através do Multiverso, onde ele encontra uma equipe de Pessoas-Aranha encarregadas de proteger sua própria existência contra uma ameaça implacável.', 2023, '2h 20m', '10', 'Aventura & Animação', 'Animação, Ação, Aventura, Ficção Científica', 8.6, 
'https://image.tmdb.org/t/p/w500/8Vt6mWEReuy4Of61Lnj5Xj704m8.jpg', 
'https://image.tmdb.org/t/p/original/4HodYYKEIsGOdinkGi2Ucz6X9i0.jpg', 
'cqGjhVJWtEg', 'Joaquim Dos Santos, Kemp Powers', 'Shameik Moore, Hailee Steinfeld, Oscar Isaac', 0),

(8, 'Encanto', 'Encanto', 'Em uma casa mágica nas montanhas da Colômbia, a família Madrigal possui dons extraordinários. Quando a magia da casa corre perigo, Mirabel, a única sem poderes, torna-se a última esperança.', 2021, '1h 42m', 'L', 'Disney & Pixar', 'Animação, Comédia, Família, Fantasia', 7.2, 
'https://image.tmdb.org/t/p/w500/4j0PNHkMr5ax3AsUsRJ2Vh63KiJ.jpg', 
'https://image.tmdb.org/t/p/original/3G1Q59J40FD9bJfMGHgqZIO9BPu.jpg', 
'CaimKeDcudo', 'Jared Bush, Byron Howard', 'Stephanie Beatriz, María Cecilia Botero, John Leguizamo', 0),

(9, 'Zootopia: Essa Cidade é o Bicho', 'Zootopia', 'Em uma metrópole de mamíferos antropomórficos, uma policial coelha novata e um esperto raposo vigarista devem trabalhar juntos para desvendar uma conspiração misteriosa.', 2016, '1h 48m', 'L', 'Disney & Pixar', 'Animação, Aventura, Comédia, Família', 8.0, 
'https://image.tmdb.org/t/p/w500/hlK0e0wAQ3VFG5R9NsHQ79J9A4i.jpg', 
'https://image.tmdb.org/t/p/original/mh0k6jT62r9fP0N784e1b3P9n9V.jpg', 
'jWM0ct-OLsM', 'Byron Howard, Rich Moore', 'Ginnifer Goodwin, Jason Bateman, Idris Elba', 0),

(10, 'Kung Fu Panda 4', 'Kung Fu Panda 4', 'Po é escolhido para se tornar o Líder Espiritual do Vale da Paz, mas precisa encontrar e treinar rapidamente um novo Guerreiro Dragão antes que uma terrível Camaleoa tome o poder.', 2024, '1h 34m', '10', 'Comédia & Diversão', 'Animação, Ação, Aventura, Comédia', 6.3, 
'https://image.tmdb.org/t/p/w500/kDp1vUBnMpe8ak4rjgl3cLELqjU.jpg', 
'https://image.tmdb.org/t/p/original/1XDDXPXGiI8id7MrUxK36ke7gkX.jpg', 
'_inKs4eeHiI', 'Mike Mitchell, Stephanie Stine', 'Jack Black, Awkwafina, Viola Davis, Dustin Hoffman', 0),

(11, 'Gato de Botas 2: O Último Pedido', 'Puss in Boots: The Last Wish', 'O Gato de Botas descobre que sua paixão pelo perigo e seu desrespeito pela segurança cobraram seu preço: ele já gastou oito de suas nove vidas. Ele agora embarca numa jornada épica para encontrar a lendária Estrela dos Desejos.', 2022, '1h 42m', '10', 'Aventura & Animação', 'Animação, Aventura, Comédia, Fantasia', 7.8, 
'https://image.tmdb.org/t/p/w500/1NqwE629USnyvvRIZg5FuW8MCvp.jpg', 
'https://image.tmdb.org/t/p/original/r9PkFnRUIthgBp2JZZBpQgIV8qX.jpg', 
'tHb7WlgyaUc', 'Joel Crawford', 'Antonio Banderas, Salma Hayek, Harvey Guillén, Wagner Moura', 0),

(12, 'Monstros S.A.', 'Monsters, Inc.', 'Na cidade dos monstros, monstros entram nos quartos das crianças à noite para assustá-las e gerar energia. Mas tudo muda quando uma adorável garotinha humana chamada Boo acidentalmente entra no mundo deles.', 2001, '1h 32m', 'L', 'Clássicos Infantis', 'Animação, Comédia, Fantasia, Família', 8.1, 
'https://image.tmdb.org/t/p/w500/w20n6qB9oQ4N7Yf1kQeT0r3v91u.jpg', 
'https://image.tmdb.org/t/p/original/70msKqK1nB7k9Gg1vN6t8pU4H6o.jpg', 
'6tCxhrTQz5U', 'Pete Docter, David Silverman', 'John Goodman, Billy Crystal, Mary Gibbs, Steve Buscemi', 0),

(13, 'Procurando Nemo', 'Finding Nemo', 'Depois que seu filho é capturado na Grande Barreira de Corais e levado para um aquário em Sydney, um tímido peixe-palhaço parte em uma jornada épica com uma peixinha esquecida chamada Dory.', 2003, '1h 40m', 'L', 'Clássicos Infantis', 'Animação, Aventura, Comédia, Família', 8.2, 
'https://image.tmdb.org/t/p/w500/eHuGQ10UpzB0ly4qdBYBEHLLjFO.jpg', 
'https://image.tmdb.org/t/p/original/b6qUvvgJ5unKvd4eR9iJ42PnvkW.jpg', 
'2zLkasScy7A', 'Andrew Stanton, Lee Unkrich', 'Albert Brooks, Ellen DeGeneres, Alexander Gould', 0),

(14, 'Frozen: Uma Aventura Congelante', 'Frozen', 'Quando seu reino fica preso em um inverno perpétuo, a otimista Anna se junta ao rude homem da montanha Kristoff e sua leal rena Sven para encontrar sua irmã Elsa e quebrar o feitiço.', 2013, '1h 42m', 'L', 'Disney & Pixar', 'Animação, Aventura, Família, Fantasia', 7.4, 
'https://image.tmdb.org/t/p/w500/kgNj8vM4bJ8o7zK6l6zG9Y9i57v.jpg', 
'https://image.tmdb.org/t/p/original/9vwhzGg8bC2m9aU1Q0o3aYq0O0A.jpg', 
'zi683bU2nvk', 'Chris Buck, Jennifer Lee', 'Kristen Bell, Idina Menzel, Jonathan Groff, Josh Gad', 0),

(15, 'Ratatouille', 'Ratatouille', 'Um ratinho com paladar refinado chamado Remy sonha em se tornar um renomado chef francês, fazendo uma aliança incomum com um atrapalhado ajudante de cozinha num renomado restaurante em Paris.', 2007, '1h 51m', 'L', 'Clássicos Infantis', 'Animação, Comédia, Família, Fantasia', 8.1, 
'https://image.tmdb.org/t/p/w500/t3vaWRPSf6W1nztMe190FaScIKv.jpg', 
'https://image.tmdb.org/t/p/original/exiiB0r3gK2nS4b5mX6gP7T9u5G.jpg', 
'NgsQ8mVuY8w', 'Brad Bird, Jan Pinkava', 'Patton Oswalt, Ian Holm, Lou Romano, Brian Dennehy', 0),

(16, 'WALL-E', 'WALL-E', 'No futuro distante, um pequeno robô coletor de lixo inadvertidamente embarca em uma jornada espacial que finalmente decidirá o destino de toda a humanidade.', 2008, '1h 38m', 'L', 'Clássicos Infantis', 'Animação, Aventura, Família, Ficção Científica', 8.4, 
'https://image.tmdb.org/t/p/w500/9cJETuLhlAOhGMnvJF9W7n9oTAz.jpg', 
'https://image.tmdb.org/t/p/original/nLBR24iHw2p3Y6Q37G0J2h2gT1X.jpg', 
'alIq_wG9FN8', 'Andrew Stanton', 'Ben Burtt, Elissa Knight, Jeff Garlin, Fred Willard', 0);

-- Inserir alguns registros no Histórico de Auditoria para demonstrar a auditoria
INSERT INTO `logs_auditoria` (`usuario_id`, `usuario_nome`, `acao`, `detalhes`, `ip`) VALUES
(1, 'Administrador CineKids', 'SISTEMA_INICIADO', 'Banco de dados CineKids inicializado e tabelas estruturadas com sucesso.', '127.0.0.1'),
(1, 'Administrador CineKids', 'CATALOGO_IMPORTADO', 'Catálogo inicial de 16 filmes infanto-juvenis cadastrado no sistema.', '127.0.0.1'),
(2, 'Turma de Logística ETEC', 'AUDITORIA_CONFIGURADA', 'Módulo de auditoria e segurança contra SQL Injection ativado.', '127.0.0.1');

-- Inserir algumas avaliações iniciais para enriquecer a experiência
INSERT INTO `avaliacoes` (`filme_id`, `usuario_id`, `nota`, `comentario`) VALUES
(1, 3, 5, 'Amei as novas emoções! A Ansiedade é muito engraçada!'),
(3, 3, 5, 'Chorei com a música Lembre de Mim, o filme mais lindo do mundo!'),
(7, 2, 5, 'Animação perfeita, melhor filme do Homem-Aranha!');

-- Inserir alguns favoritos iniciais para o usuário kid
INSERT INTO `favoritos` (`filme_id`, `usuario_id`) VALUES
(1, 3),
(3, 3),
(7, 3);
