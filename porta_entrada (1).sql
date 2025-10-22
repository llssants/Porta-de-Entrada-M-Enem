-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/10/2025 às 13:30
-- Versão do servidor: 8.0.39
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `porta_entrada`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alternativas`
--

CREATE TABLE `alternativas` (
  `id_alternativa` int NOT NULL,
  `id_questao` int NOT NULL,
  `texto` varchar(500) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `correta` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `alternativas`
--

INSERT INTO `alternativas` (`id_alternativa`, `id_questao`, `texto`, `imagem`, `correta`) VALUES
(81, 19, '50', NULL, 0),
(82, 19, '75', NULL, 1),
(83, 19, '100', NULL, 0),
(84, 19, '125', NULL, 0),
(85, 20, 'I, V, cadeia (feedback)', NULL, 0),
(86, 20, 'I, III, IV', NULL, 1),
(87, 20, 'III, V, I, II', NULL, 0),
(88, 20, 'III, IV', NULL, 0),
(89, 20, 'V, II, II, I', NULL, 0),
(90, 23, 'a) ácidos nucléicos', NULL, 0),
(91, 23, 'b) carboidratos', NULL, 0),
(92, 23, 'c) lipídios', NULL, 0),
(93, 23, 'd) proteínas', NULL, 1),
(94, 23, 'e) vitaminas', NULL, 0),
(95, 24, 'a) Em I fermentação e X água', NULL, 0),
(96, 24, 'b) Em I respiração e X álcool', NULL, 0),
(97, 24, 'c) Em II fermentação e Y água', NULL, 0),
(98, 24, 'd) Em II respiração e Y álcool', NULL, 0),
(99, 24, 'e) Em I respiração e X água', NULL, 1),
(100, 25, 'a) enzimas', NULL, 0),
(101, 25, 'b) catalisadores', NULL, 1),
(102, 25, 'c) ionizadores', NULL, 0),
(103, 25, 'd) substâncias orgânicas', NULL, 0),
(104, 25, 'e) substâncias inorgânicas', NULL, 0),
(105, 26, 'a) ácido láctico devido a processos anaeróbios', NULL, 1),
(106, 26, 'b) ácido láctico devido a processos aeróbios', NULL, 0),
(107, 26, 'c) glicogênio nas células devido à falta de oxigênio', NULL, 0),
(108, 26, 'd) glicogênio no sangue devido à transpiração intensa', NULL, 0),
(109, 26, 'e) sais e à falta de glicose devido ao esforço', NULL, 0),
(110, 27, 'a) varia entre os tecidos do corpo de um indivíduo', NULL, 0),
(111, 27, 'b) é o mesmo em todas as células de um indivíduo, mas varia de indivíduo para indivíduo', NULL, 0),
(112, 27, 'c) é o mesmo nos indivíduos de uma mesma espécie, mas varia de espécie para espécie', NULL, 0),
(113, 27, 'd) permite distinguir procariotos de eucariotos', NULL, 0),
(114, 27, 'e) é praticamente o mesmo em todas as formas de vida', NULL, 1),
(115, 28, 'a) plantas', NULL, 0),
(116, 28, 'b) bactérias', NULL, 1),
(117, 28, 'c) musgos', NULL, 0),
(118, 28, 'd) fungos', NULL, 0),
(119, 28, 'e) algas', NULL, 0),
(120, 29, 'a) treonina, arginina, leucina', NULL, 0),
(121, 29, 'b) arginina, leucina, treonina', NULL, 0),
(122, 29, 'c) leucina, arginina, treonina', NULL, 0),
(123, 29, 'd) treonina, leucina, arginina', NULL, 0),
(124, 29, 'e) leucina, treonina, arginina', NULL, 1),
(125, 30, 'a) usar carbono orgânico e carbono inorgânico', NULL, 0),
(126, 30, 'b) usar carbono inorgânico e carbono orgânico', NULL, 1),
(127, 30, 'c) usar carbono da água e do ar', NULL, 0),
(128, 30, 'd) usar metano e gás carbônico', NULL, 0),
(129, 30, 'e) realizar respiração aeróbia e fermentação', NULL, 0),
(130, 31, 'a) inchaço abdominal devido a vermes', NULL, 0),
(131, 31, 'b) dieta rica em carboidratos compromete síntese protéica', NULL, 1),
(132, 31, 'c) conteúdo protéico do sangue diminui causando inchaço', NULL, 0),
(133, 31, 'd) falta de proteínas altera turgescência das células', NULL, 0),
(134, 31, 'e) aminoácidos presentes nos carboidratos interferem na produção de proteínas', NULL, 0),
(135, 32, 'a) servir como doador de elétrons', NULL, 0),
(136, 32, 'b) funcionar como regulador térmico', NULL, 1),
(137, 32, 'c) agir como solvente universal', NULL, 0),
(138, 32, 'd) transportar íons de ferro e magnésio', NULL, 0),
(139, 32, 'e) manter metabolismo nos organismos vivos', NULL, 0),
(140, 33, 'a) ATP', NULL, 1),
(141, 33, 'b) glicose', NULL, 0),
(142, 33, 'c) lactato', NULL, 0),
(143, 33, 'd) CO2', NULL, 0),
(144, 33, 'e) água', NULL, 0),
(145, 34, 'a) reações químicas', NULL, 1),
(146, 34, 'b) transporte de oxigênio', NULL, 0),
(147, 34, 'c) síntese de lipídios', NULL, 0),
(148, 34, 'd) digestão mecânica', NULL, 0),
(149, 34, 'e) transporte de íons', NULL, 0),
(150, 35, 'a) Armazenar informações genéticas', NULL, 0),
(151, 35, 'b) Fornecer energia para reações', NULL, 1),
(152, 35, 'c) Regular pH celular', NULL, 0),
(153, 35, 'd) Sintetizar proteínas', NULL, 0),
(154, 35, 'e) Transportar oxigênio', NULL, 0),
(155, 36, 'a) respiração anaeróbia', NULL, 1),
(156, 36, 'b) respiração aeróbia', NULL, 0),
(157, 36, 'c) glicogenólise', NULL, 0),
(158, 36, 'd) fotossíntese', NULL, 0),
(159, 36, 'e) fermentação alcoólica', NULL, 0),
(160, 37, 'a) hormônios', NULL, 0),
(161, 37, 'b) enzimas', NULL, 1),
(162, 37, 'c) vitaminas', NULL, 0),
(163, 37, 'd) carboidratos', NULL, 0),
(164, 37, 'e) lipídios', NULL, 0),
(165, 38, 'a) autotróficos usam carbono inorgânico', NULL, 1),
(166, 38, 'b) heterotróficos usam carbono inorgânico', NULL, 0),
(167, 38, 'c) autotróficos usam carbono orgânico', NULL, 0),
(168, 38, 'd) heterotróficos usam CO2', NULL, 0),
(169, 38, 'e) ambos usam metano', NULL, 0),
(170, 39, 'a) síntese de proteínas e transporte celular', NULL, 1),
(171, 39, 'b) armazenar informações genéticas', NULL, 0),
(172, 39, 'c) manter pH celular', NULL, 0),
(173, 39, 'd) fotossíntese', NULL, 0),
(174, 39, 'e) dividir células', NULL, 0),
(175, 40, 'a) regular temperatura corporal', NULL, 1),
(176, 40, 'b) realizar respiração', NULL, 0),
(177, 40, 'c) fotossíntese', NULL, 0),
(178, 40, 'd) transportar nutrientes', NULL, 0),
(179, 40, 'e) aumentar massa', NULL, 0),
(180, 41, 'a) reações químicas', NULL, 1),
(181, 41, 'b) transporte de oxigênio', NULL, 0),
(182, 41, 'c) síntese de lipídios', NULL, 0),
(183, 41, 'd) digestão mecânica', NULL, 0),
(184, 41, 'e) transporte de íons', NULL, 0),
(185, 42, 'a) autotróficos usam carbono inorgânico', NULL, 1),
(186, 42, 'b) heterotróficos usam carbono inorgânico', NULL, 0),
(187, 42, 'c) autotróficos usam carbono orgânico', NULL, 0),
(188, 42, 'd) heterotróficos usam CO2', NULL, 0),
(189, 42, 'e) ambos usam metano', NULL, 0),
(190, 43, 'a) síntese de proteínas e transporte celular', NULL, 1),
(191, 43, 'b) armazenar informações genéticas', NULL, 0),
(192, 43, 'c) manter pH celular', NULL, 0),
(193, 43, 'd) fotossíntese', NULL, 0),
(194, 43, 'e) dividir células', NULL, 0),
(195, 44, 'a) respiração anaeróbia', NULL, 1),
(196, 44, 'b) respiração aeróbia', NULL, 0),
(197, 44, 'c) glicogenólise', NULL, 0),
(198, 44, 'd) fotossíntese', NULL, 0),
(199, 44, 'e) fermentação alcoólica', NULL, 0),
(200, 45, 'a) regular temperatura corporal', NULL, 1),
(201, 45, 'b) realizar respiração', NULL, 0),
(202, 45, 'c) fotossíntese', NULL, 0),
(203, 45, 'd) transportar nutrientes', NULL, 0),
(204, 45, 'e) aumentar massa', NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `desempenho`
--

CREATE TABLE `desempenho` (
  `id_usuario` int NOT NULL,
  `acertos` int DEFAULT '0',
  `media_redacao` float DEFAULT '0',
  `erros` int DEFAULT '0',
  `pontos` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `disciplinas`
--

CREATE TABLE `disciplinas` (
  `id_disciplina` int NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `disciplinas`
--

INSERT INTO `disciplinas` (`id_disciplina`, `nome`) VALUES
(12, 'Artes'),
(8, 'Biologia'),
(5, 'Direito'),
(3, 'Filosofia'),
(6, 'Física'),
(9, 'Geociências'),
(2, 'Geografia'),
(1, 'História'),
(11, 'Inglês'),
(13, 'Literatura'),
(14, 'Matemática'),
(10, 'Português'),
(7, 'Química'),
(4, 'Sociologia');

-- --------------------------------------------------------

--
-- Estrutura para tabela `questoes`
--

CREATE TABLE `questoes` (
  `id_questao` int NOT NULL,
  `enunciado` text NOT NULL,
  `imagem_enunciado` varchar(255) DEFAULT NULL,
  `id_disciplina` int NOT NULL,
  `dificuldade` enum('facil','medio','dificil') DEFAULT 'medio',
  `id_usuario` int DEFAULT NULL,
  `origem` varchar(100) DEFAULT NULL,
  `ano` year DEFAULT NULL,
  `id_topico` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `questoes`
--

INSERT INTO `questoes` (`id_questao`, `enunciado`, `imagem_enunciado`, `id_disciplina`, `dificuldade`, `id_usuario`, `origem`, `ano`, `id_topico`) VALUES
(19, 'Considere o esquema no qual uma pessoa sustenta um peso preso ao punho a 31 cm do ponto de inserção do músculo. Em outro esquema, o mesmo peso está a 14 cm. Admita que o consumo de ATP por minuto é proporcional à força muscular. No esquema 1, o consumo foi de 0,3 mol. A quantidade de glicose consumida no esquema 2, em 1 minuto, foi igual, em milimol, a:', NULL, 8, 'medio', 1, 'UERJ', '2007', 1),
(20, 'Espécies de peixes sobem até as cabeceiras dos rios nadando contra a correnteza para reprodução. Esse fenômeno é a piracema. Com base nas informações dadas sobre estímulos ambientais, produção de hormônios e comportamento dos peixes, aponte a alternativa que classifica corretamente as razões que levam à ocorrência da piracema.', NULL, 8, 'medio', 1, 'ETEs', '2007', 1),
(23, '(Fuvest-1998) Leia o texto de Jacob Berzelius sobre processos catalíticos em animais e plantas. A capacidade de os organismos vivos produzirem compostos químicos deve-se a:', NULL, 8, 'facil', 1, 'Fuvest', '1998', 1),
(24, '(Vunesp-1999) No esquema de produção de energia, assinale a alternativa correta:', NULL, 8, 'facil', 1, 'Vunesp', '1999', 1),
(25, '(Cesgranrio-1994) Com base na experiência com água oxigenada, conclui-se que o dióxido de manganês e a substância liberada pelo fígado são:', NULL, 8, 'facil', 1, 'Cesgranrio', '1994', 1),
(26, '(PUCCamp-2005) Em provas de corrida de longa distância, a musculatura pode ficar dolorida devido ao acúmulo de:', NULL, 8, 'facil', 1, 'PUCCamp', '2005', 1),
(27, '(Fuvest-2001) Sobre o código genético, é correto afirmar que:', NULL, 8, 'medio', 1, 'Fuvest', '2001', 1),
(28, '(Mack-2001) As duas equações representam processos realizados por alguns tipos de:', NULL, 8, 'medio', 1, 'Mack', '2001', 1),
(29, '(UNIFESP-2001) Sequência de aminoácidos correspondente ao DNA GAC TGA TCT:', NULL, 8, 'medio', 1, 'UNIFESP', '2001', 1),
(30, '(PUC-RJ-2008) Diferença essencial entre seres autotróficos e heterotróficos:', NULL, 8, 'facil', 1, 'PUC-RJ', '2008', 1),
(31, '(UFPR-2009) Sobre desnutrição Kwashiokor causada por dieta pobre em proteínas, assinale a alternativa correta:', NULL, 8, 'medio', 1, 'UFPR', '2009', 1),
(32, '(Simulado Enem-2009) A propriedade físico-química do calor latente de vaporização confere à água a capacidade de:', NULL, 8, 'facil', 1, 'Enem', '2009', 1),
(33, '(Unicamp-2010) Processo de respiração aeróbica resulta em produção de energia principalmente na forma de:', NULL, 8, 'facil', 1, 'Unicamp', '2010', 1),
(34, '(Mackenzie-2011) Enzimas atuam como catalisadores biológicos acelerando:', NULL, 8, 'medio', 1, 'Mackenzie', '2011', 1),
(35, '(PUC-SP-2012) Função do ATP nas células:', NULL, 8, 'facil', 1, 'PUC-SP', '2012', 1),
(36, '(Fuvest-2013) O ácido láctico é produzido em músculos em esforço intenso devido à:', NULL, 8, 'facil', 1, 'Fuvest', '2013', 1),
(37, '(Mack-2014) Substâncias catalisadoras biológicas são chamadas de:', NULL, 8, 'facil', 1, 'Mack', '2014', 1),
(38, '(UNESP-2015) Diferença entre organismos autotróficos e heterotróficos:', NULL, 8, 'medio', 1, 'UNESP', '2015', 1),
(39, '(UFPR-2016) O ATP fornece energia para:', NULL, 8, 'facil', 1, 'UFPR', '2016', 1),
(40, '(Enem-2011) A água possui calor latente de vaporização, permitindo:', NULL, 8, 'medio', 1, 'Enem', '2011', 1),
(41, '(Mack-2018) Enzimas aceleram:', NULL, 8, 'facil', 1, 'Mack', '2018', 1),
(42, '(PUC-RJ-2019) Diferença entre organismos autotróficos e heterotróficos:', NULL, 8, 'facil', 1, 'PUC-RJ', '2019', 1),
(43, '(Unicamp-2020) ATP é a principal molécula de energia celular, utilizada para:', NULL, 8, 'facil', 1, 'Unicamp', '2020', 1),
(44, '(Mack-2021) O ácido láctico produzido nos músculos indica:', NULL, 8, 'medio', 1, 'Mack', '2021', 1),
(45, '(Enem-2022) Água possui calor latente de vaporização, permitindo:', NULL, 8, 'medio', 1, 'Enem', '2022', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `questoes_feitas`
--

CREATE TABLE `questoes_feitas` (
  `id` bigint UNSIGNED NOT NULL,
  `id_usuario` int NOT NULL,
  `id_questao` int NOT NULL,
  `acertou` tinyint(1) NOT NULL,
  `data_resposta` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ranking`
--

CREATE TABLE `ranking` (
  `id_usuario` int NOT NULL,
  `pontos` int DEFAULT '0',
  `nivel` int DEFAULT '1',
  `medalhas` varchar(255) DEFAULT NULL,
  `data_ultimo_checkpoint` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `redacoes`
--

CREATE TABLE `redacoes` (
  `id_redacao` int NOT NULL,
  `texto` text NOT NULL,
  `nota_c1` int DEFAULT NULL,
  `nota_c2` int DEFAULT NULL,
  `nota_c3` int DEFAULT NULL,
  `nota_c4` int DEFAULT NULL,
  `nota_c5` int DEFAULT NULL,
  `data_envio` date DEFAULT NULL,
  `id_usuario` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `topico`
--

CREATE TABLE `topico` (
  `id_topico` int NOT NULL,
  `nome` varchar(255) NOT NULL,
  `id_disciplina` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `topico`
--

INSERT INTO `topico` (`id_topico`, `nome`, `id_disciplina`) VALUES
(1, 'Bioquímica', 8);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `Telefone` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `senha`, `Telefone`) VALUES
(1, 'Yago', 'yago@gmail.com', '$2y$10$AGBhOuZDBNpezo0ylGtEAOgUdsWsZsZKZ29rT6HJGpVy.tj6IKFAe', '11934450936');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alternativas`
--
ALTER TABLE `alternativas`
  ADD PRIMARY KEY (`id_alternativa`),
  ADD KEY `id_questao` (`id_questao`);

--
-- Índices de tabela `desempenho`
--
ALTER TABLE `desempenho`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Índices de tabela `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD PRIMARY KEY (`id_disciplina`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `questoes`
--
ALTER TABLE `questoes`
  ADD PRIMARY KEY (`id_questao`),
  ADD KEY `id_disciplina` (`id_disciplina`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `questoes_feitas`
--
ALTER TABLE `questoes_feitas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`,`id_questao`),
  ADD KEY `id_questao` (`id_questao`);

--
-- Índices de tabela `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Índices de tabela `redacoes`
--
ALTER TABLE `redacoes`
  ADD PRIMARY KEY (`id_redacao`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `topico`
--
ALTER TABLE `topico`
  ADD PRIMARY KEY (`id_topico`),
  ADD KEY `id_disciplina` (`id_disciplina`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alternativas`
--
ALTER TABLE `alternativas`
  MODIFY `id_alternativa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;

--
-- AUTO_INCREMENT de tabela `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id_disciplina` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de tabela `questoes`
--
ALTER TABLE `questoes`
  MODIFY `id_questao` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de tabela `questoes_feitas`
--
ALTER TABLE `questoes_feitas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `redacoes`
--
ALTER TABLE `redacoes`
  MODIFY `id_redacao` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `topico`
--
ALTER TABLE `topico`
  MODIFY `id_topico` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `alternativas`
--
ALTER TABLE `alternativas`
  ADD CONSTRAINT `alternativas_ibfk_1` FOREIGN KEY (`id_questao`) REFERENCES `questoes` (`id_questao`) ON DELETE CASCADE;

--
-- Restrições para tabelas `desempenho`
--
ALTER TABLE `desempenho`
  ADD CONSTRAINT `desempenho_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Restrições para tabelas `questoes`
--
ALTER TABLE `questoes`
  ADD CONSTRAINT `questoes_ibfk_1` FOREIGN KEY (`id_disciplina`) REFERENCES `disciplinas` (`id_disciplina`),
  ADD CONSTRAINT `questoes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Restrições para tabelas `questoes_feitas`
--
ALTER TABLE `questoes_feitas`
  ADD CONSTRAINT `questoes_feitas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `questoes_feitas_ibfk_2` FOREIGN KEY (`id_questao`) REFERENCES `questoes` (`id_questao`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ranking`
--
ALTER TABLE `ranking`
  ADD CONSTRAINT `ranking_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Restrições para tabelas `redacoes`
--
ALTER TABLE `redacoes`
  ADD CONSTRAINT `redacoes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Restrições para tabelas `topico`
--
ALTER TABLE `topico`
  ADD CONSTRAINT `topico_ibfk_1` FOREIGN KEY (`id_disciplina`) REFERENCES `disciplinas` (`id_disciplina`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
