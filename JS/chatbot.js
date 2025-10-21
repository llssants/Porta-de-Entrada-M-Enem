document.addEventListener("DOMContentLoaded", () => {
    const messagesContainer = document.getElementById("messages");
    const chatInput = document.getElementById("chatInput");
    const sendButton = document.getElementById("sendButton");

    // Mensagem de boas-vindas
    addMessage("Olá! Eu sou o assistente virtual do Porta de Entrada. Como posso ajudar você?", "bot");

    // Evento de clique no botão
    sendButton.addEventListener("click", enviarMensagem);

    // Evento de pressionar Enter
    chatInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") enviarMensagem();
    });

    function enviarMensagem() {
        const mensagem = chatInput.value.trim();
        if (mensagem === "") return;

        addMessage(mensagem, "user");
        chatInput.value = "";

        const resposta = gerarResposta(mensagem);
        setTimeout(() => {
            addMessage(resposta, "bot");
        }, 600); // simula tempo de resposta
    }

    function addMessage(texto, tipo) {
        const msg = document.createElement("div");
        msg.classList.add("message", tipo);
        msg.innerHTML = `<div class="avatar"></div><div class="text">${texto}</div>`;
        messagesContainer.appendChild(msg);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function gerarResposta(pergunta) {
        const msg = pergunta.toLowerCase();

        if (msg.includes("olá") || msg.includes("oi")) {
            return "Olá! Como posso ajudar você hoje?";
        } else if (msg.includes("tudo bem")) {
            return "Estou bem, obrigado por perguntar! E você?";
        } else if (msg.includes("ajuda") || msg.includes("preciso de ajuda")) {
            return "Claro! Me diga com o que você precisa ajuda.";
        } else if (msg.includes("horário") || msg.includes("hora")) {
            return "Nosso horário de funcionamento é integral, de segunda a segunda.";
        } else if (msg.includes("contato") || msg.includes("telefone")) {
            return "Você pode entrar em contato pelo email: gabinete@muz.ifsuldeminas.edu.br.";
        } else if (msg.includes("onde fica") || msg.includes("localização") || msg.includes("endereço")) {
            return "Estamos localizados no Instituto Federal do Sul de Minas - Campus Muzambinho.";
        } else if (msg.includes("quem é você") || msg.includes("o que você faz")) {
            return "Sou um assistente virtual criado para ajudar com dúvidas sobre o projeto Porta de Entrada.";
        } else if (msg.includes("obrigado") || msg.includes("valeu")) {
            return "De nada! Se precisar de mais alguma coisa, é só chamar.";
        } else if (msg.includes("tchau") || msg.includes("até mais")) {
            return "Até mais! Tenha um ótimo dia!";
        } else if (msg.includes("cronograma") || msg.includes("agenda")) {
            return "O cronograma inclui aulas semanais, atividades práticas e encontros online.";
        } else if (msg.includes("videoaula") || msg.includes("vídeo aula") || msg.includes("aulas online")) {
            return "As videoaulas são disponibilizadas semanalmente na plataforma do projeto.";
        } else if (msg.includes("instituto") || msg.includes("quem organiza") || msg.includes("quem criou")) {
            return "O projeto é uma iniciativa do Instituto Federal do Sul de Minas, campus Muzambinho.";
        } else if (msg.includes("como entrar") || msg.includes("inscrição") || msg.includes("participar")) {
            return "As inscrições são abertas periodicamente e divulgadas no site e redes sociais do Instituto.";
        } else if (msg.includes("origem do projeto") || msg.includes("por que foi criado") || msg.includes("história")) {
            return "O projeto surgiu para promover inclusão digital e apoio educacional à comunidade.";
        } else if (msg.includes("certificado") || msg.includes("comprovação")) {
            return "Sim, os participantes recebem certificado ao final do projeto.";
        } else if (msg.includes("gratuito") || msg.includes("tem custo")) {
            return "Sim, o projeto é totalmente gratuito e aberto à comunidade.";
        } else if (msg.includes("idade mínima") || msg.includes("quem pode participar")) {
            return "Qualquer pessoa acima de 14 anos pode participar, sem necessidade de escolaridade específica.";
        } else if (msg.includes("plataforma") || msg.includes("como acessar")) {
            return "Você acessa a plataforma pelo site oficial do projeto, com login e senha fornecidos após a inscrição.";
        } else if (msg.includes("duração") || msg.includes("quanto tempo")) {
            return "O projeto tem duração média de 3 meses, com atividades semanais.";
        } else if (msg.includes("tem prova") || msg.includes("avaliação")) {
            return "Não há provas tradicionais, mas sim atividades práticas e participação nos encontros.";
        } else if (msg.includes("tem presencial") || msg.includes("presencial")) {
            return "A maioria das atividades é online, mas podem ocorrer encontros presenciais opcionais.";
        } else if (msg.includes("tem suporte") || msg.includes("dúvidas")) {
            return "Sim, você pode tirar dúvidas com os tutores pelo chat ou e-mail.";
        } else if (msg.includes("tem grupo") || msg.includes("whatsapp")) {
            return "Sim, após a inscrição você pode entrar no grupo de WhatsApp para receber avisos e trocar ideias.";
        } else if (msg.includes("tem certificado digital")) {
            return "Sim, o certificado é digital e pode ser baixado após a conclusão.";
        } else if (msg.includes("tem limite de vagas")) {
            return "Sim, as vagas são limitadas. Por isso, é importante se inscrever o quanto antes.";
        } else if (msg.includes("tem lista de espera")) {
            return "Se as vagas estiverem preenchidas, você pode entrar na lista de espera para a próxima turma.";
        } else if (msg.includes("tem material de apoio")) {
            return "Sim, o projeto oferece apostilas, vídeos e links úteis para complementar os estudos.";
        } else if (msg.includes("tem certificado reconhecido")) {
            return "O certificado é emitido pelo Instituto Federal e tem validade como atividade de extensão.";
        } else if (msg.includes("tem atividades práticas")) {
            return "Sim, as atividades práticas são parte essencial do projeto e ajudam na fixação do conteúdo.";
        } else if (msg.includes("tem tutoria")) {
            return "Sim, os tutores acompanham os alunos e ajudam com dúvidas e orientações.";
        } else {
            return "Desculpe, ainda estou aprendendo. Poderia reformular sua pergunta?";
        }
    }
});
