
document.addEventListener("DOMContentLoaded", () => {
    const messagesContainer = document.getElementById("messages");
    const chatInput = document.getElementById("chatInput");
    const sendButton = document.getElementById("sendButton");

    const botResponses = {
        "olá": "Olá! Como posso ajudar você hoje?",
        "ola": "Olá! Como posso ajudar você hoje?",
        "tudo bem": "Estou bem, obrigado por perguntar! E você?",
        "o que você faz": "Eu sou um chatbot e posso responder a algumas perguntas e ajudar com informações sobre o projeto.",
        "ajuda": "Claro! Em que posso te ajudar? Posso falar sobre o projeto, seus objetivos ou funcionalidades.",
        "quem é você": "Eu sou um assistente virtual criado para este projeto. Prazer em conhecê-lo!",
        "obrigado": "De nada! Se precisar de mais alguma coisa, é só perguntar.",
        "tchau": "Até mais! Tenha um ótimo dia!",
        "default": "Desculpe, não entendi sua pergunta. Poderia reformular ou perguntar de outra forma?"
    };

    function addMessage(text, sender) {
        const messageElement = document.createElement("div");
        messageElement.classList.add("message", sender);
        messageElement.innerHTML = `<div class="avatar"></div><div class="text">${text}</div>`;
        messagesContainer.appendChild(messageElement);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function getBotResponse(userMessage) {
        const lowerCaseMessage = userMessage.toLowerCase();
        
        // Check for exact matches first
        if (botResponses[lowerCaseMessage]) {
            return botResponses[lowerCaseMessage];
        }
        
        // Then check for partial matches
        for (const key in botResponses) {
            if (lowerCaseMessage.includes(key) && key !== "default") {
                return botResponses[key];
            }
        }
        
        return botResponses["default"];
    }

    sendButton.addEventListener("click", () => {
        const userMessage = chatInput.value.trim();
        if (userMessage) {
            addMessage(userMessage, "user");
            chatInput.value = "";

            setTimeout(() => {
                const botResponse = getBotResponse(userMessage);
                addMessage(botResponse, "bot");
            }, 1000);
        }
    });

    chatInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            sendButton.click();
        }
    });

    // Mensagem de boas-vindas inicial do bot
    addMessage("Olá! Eu sou o assistente virtual do Porta de Entrada. Como posso ajudar você?", "bot");
});

