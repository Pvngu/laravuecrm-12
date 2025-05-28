<template>
    <div
        class="flex flex-col w-full border rounded-lg shadow-md overflow-hidden"
        :style="{ height: 'calc(100vh - 220px)' }"
    >
        <!-- Header -->
        <div
            class="bg-emerald-600 text-white p-4 flex items-center justify-between"
        >
            <div class="flex items-center">
                <div
                    class="w-10 h-10 rounded-full bg-emerald-400 flex items-center justify-center mr-3"
                >
                    <span
                        v-if="!selectedContact.avatar"
                        class="text-white font-bold"
                    >
                        {{ selectedContact.name.charAt(0) }}
                    </span>
                    <img
                        v-else
                        :src="selectedContact.avatar"
                        class="w-10 h-10 rounded-full object-cover"
                        alt="Contact avatar"
                    />
                </div>
                <div>
                    <h3 class="font-bold">{{ selectedContact.name }}</h3>
                    <p class="text-xs opacity-80">
                        {{ chatMode === "whatsapp" ? "Online" : "SMS" }}
                    </p>
                </div>
            </div>

            <!-- Toggle between SMS and WhatsApp -->
            <div class="flex items-center">
                <button
                    @click="toggleChatMode"
                    class="bg-emerald-700 hover:bg-emerald-800 text-white rounded-md px-3 py-1 text-sm"
                >
                    Switch to {{ chatMode === "whatsapp" ? "SMS" : "WhatsApp" }}
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div
            ref="messagesContainer"
            class="flex-1 p-4 overflow-y-auto bg-gray-100"
            :class="{
                'bg-gray-100': chatMode === 'sms',
                'bg-[#e5ded8]': chatMode === 'whatsapp',
            }"
            @scroll="handleScroll"
        >
            <!-- Loading indicator -->
            <div v-if="isLoading" class="flex justify-center py-2">
                <div class="loader">
                    <svg
                        class="animate-spin h-5 w-5 text-emerald-500"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                </div>
            </div>

            <!-- Date separator -->
            <div v-if="messages.length > 0" class="flex justify-center my-3">
                <div
                    class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full"
                >
                    {{ formatDate(messages[0].timestamp) }}
                </div>
            </div>

            <!-- Messages -->
            <div
                v-for="(message, index) in messages"
                :key="message.id"
                class="mb-4"
            >
                <!-- Date separator for new days -->
                <div
                    v-if="
                        index > 0 &&
                        isNewDay(
                            message.timestamp,
                            messages[index - 1].timestamp
                        )
                    "
                    class="flex justify-center my-3"
                >
                    <div
                        class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full"
                    >
                        {{ formatDate(message.timestamp) }}
                    </div>
                </div>

                <div
                    class="max-w-[80%] p-3 rounded-lg relative"
                    :class="{
                        'ml-auto bg-emerald-500 text-white rounded-tr-none':
                            message.sent,
                        'mr-auto bg-white text-gray-800 rounded-tl-none':
                            !message.sent,
                        'rounded-tr-none':
                            message.sent && chatMode === 'whatsapp',
                        'rounded-tl-none':
                            !message.sent && chatMode === 'whatsapp',
                        'rounded-br-none': message.sent && chatMode === 'sms',
                        'rounded-bl-none': !message.sent && chatMode === 'sms',
                    }"
                >
                    {{ message.text }}
                    <div class="text-xs mt-1 opacity-70 text-right">
                        {{ formatTime(message.timestamp) }}
                        <span
                            v-if="message.sent && chatMode === 'whatsapp'"
                            class="ml-1"
                        >
                            ✓✓
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 border-t bg-white">
            <form @submit.prevent="sendMessage" class="flex items-center">
                <button
                    type="button"
                    class="p-2 rounded-full text-gray-500 hover:bg-gray-100"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-smile"
                    >
                        <circle cx="12" cy="12" r="10" />
                        <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                        <line x1="9" x2="9.01" y1="9" y2="9" />
                        <line x1="15" x2="15.01" y1="9" y2="9" />
                    </svg>
                </button>
                <button
                    type="button"
                    class="p-2 rounded-full text-gray-500 hover:bg-gray-100"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-paperclip"
                    >
                        <path
                            d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"
                        />
                    </svg>
                </button>
                <input
                    v-model="newMessage"
                    type="text"
                    placeholder="Type a message"
                    class="flex-1 p-2 mx-2 border rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-500"
                />
                <button
                    type="submit"
                    class="p-2 rounded-full bg-emerald-500 text-white hover:bg-emerald-600"
                >
                    <svg
                        v-if="chatMode === 'whatsapp'"
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-mic"
                    >
                        <path
                            d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"
                        />
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                        <line x1="12" x2="12" y1="19" y2="22" />
                    </svg>
                    <svg
                        v-else
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-send"
                    >
                        <path d="m22 2-7 20-4-9-9-4Z" />
                        <path d="M22 2 11 13" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from "vue";

// Chat mode: 'sms' or 'whatsapp'
const chatMode = ref("whatsapp");

// Selected contact
const selectedContact = ref({
    id: 1,
    name: "John Doe",
    avatar: "",
    lastSeen: new Date(),
});

// Message history with unique IDs
const messages = ref([
    {
        id: 3,
        text: "Hi! This is an older message from earlier today.",
        sent: false,
        timestamp: new Date(Date.now() - 7200000),
    },
    {
        id: 4,
        text: "Hello! Yes, I received your previous message.",
        sent: true,
        timestamp: new Date(Date.now() - 7100000),
    },
    {
        id: 5,
        text: "Hey there! How are you doing?",
        sent: false,
        timestamp: new Date(Date.now() - 3600000),
    },
    {
        id: 6,
        text: "I'm good, thanks for asking! Just working on a new project.",
        sent: true,
        timestamp: new Date(Date.now() - 3500000),
    },
    {
        id: 7,
        text: "That sounds interesting. What kind of project is it?",
        sent: false,
        timestamp: new Date(Date.now() - 3400000),
    },
    {
        id: 8,
        text: "It's a Vue.js chat component that can switch between SMS and WhatsApp styles.",
        sent: true,
        timestamp: new Date(Date.now() - 3300000),
    },
    {
        id: 9,
        text: "Wow, that sounds cool! Can you show me when it's done?",
        sent: false,
        timestamp: new Date(Date.now() - 60000),
    },
]);

// Pagination and loading state
const page = ref(1);
const isLoading = ref(false);
const hasMoreMessages = ref(true);
const scrollThreshold = 100; // pixels from top to trigger loading
const messageIdCounter = ref(10); // For generating unique IDs for new messages

// New message input
const newMessage = ref("");
const messagesContainer = ref(null);

// Toggle between SMS and WhatsApp modes
const toggleChatMode = () => {
    chatMode.value = chatMode.value === "sms" ? "whatsapp" : "sms";
};

// Format timestamp for time display
const formatTime = (timestamp) => {
    return new Intl.DateTimeFormat("en-US", {
        hour: "numeric",
        minute: "numeric",
        hour12: true,
    }).format(timestamp);
};

// Format date for date separators
const formatDate = (timestamp) => {
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);

    const messageDate = new Date(timestamp);

    if (messageDate.toDateString() === today.toDateString()) {
        return "Today";
    } else if (messageDate.toDateString() === yesterday.toDateString()) {
        return "Yesterday";
    } else {
        return new Intl.DateTimeFormat("en-US", {
            month: "short",
            day: "numeric",
            year:
                messageDate.getFullYear() !== today.getFullYear()
                    ? "numeric"
                    : undefined,
        }).format(messageDate);
    }
};

// Check if a message is from a different day than the previous one
const isNewDay = (currentTimestamp, previousTimestamp) => {
    const current = new Date(currentTimestamp);
    const previous = new Date(previousTimestamp);

    return current.toDateString() !== previous.toDateString();
};

// Send a new message
const sendMessage = () => {
    if (newMessage.value.trim() === "") return;

    // Add the message to the conversation
    messages.value.push({
        id: messageIdCounter.value++,
        text: newMessage.value,
        sent: true,
        timestamp: new Date(),
    });

    // Clear the input
    newMessage.value = "";

    // Simulate a reply after a short delay
    setTimeout(() => {
        messages.value.push({
            id: messageIdCounter.value++,
            text: "Thanks for your message! This is an automated reply.",
            sent: false,
            timestamp: new Date(),
        });

        // Scroll to bottom after adding the reply
        scrollToBottom();
    }, 1000);
};

// Scroll to the bottom of the messages container
const scrollToBottom = async () => {
    await nextTick();
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop =
            messagesContainer.value.scrollHeight;
    }
};

// Handle scroll event for infinite scrolling
const handleScroll = () => {
    if (!messagesContainer.value || isLoading.value || !hasMoreMessages.value)
        return;

    // Check if user has scrolled near the top
    if (messagesContainer.value.scrollTop < scrollThreshold) {
        loadOlderMessages();
    }
};

// Load older messages
const loadOlderMessages = async () => {
    if (isLoading.value || !hasMoreMessages.value) return;

    isLoading.value = true;

    // Store current scroll height and position for maintaining scroll position
    const scrollHeight = messagesContainer.value.scrollHeight;

    // Simulate API call with timeout
    await new Promise((resolve) => setTimeout(resolve, 1000));

    // Generate older messages based on the current page
    const olderMessages = generateOlderMessages(page.value);

    if (olderMessages.length > 0) {
        // Add older messages to the beginning of the array
        messages.value = [...olderMessages, ...messages.value];
        page.value++;

        // If we've loaded enough pages, simulate running out of messages
        if (page.value > 3) {
            hasMoreMessages.value = false;
        }

        // After DOM update, maintain scroll position
        await nextTick();
        if (messagesContainer.value) {
            const newScrollHeight = messagesContainer.value.scrollHeight;
            messagesContainer.value.scrollTop = newScrollHeight - scrollHeight;
        }
    } else {
        hasMoreMessages.value = false;
    }

    isLoading.value = false;
};

// Generate older messages for infinite scrolling simulation
const generateOlderMessages = (page) => {
    // This would normally be an API call to fetch older messages
    const messagesPerPage = 5;
    const baseDate = new Date(Date.now() - page * 86400000); // One day earlier per page

    const olderMessages = [];

    for (let i = 0; i < messagesPerPage; i++) {
        const id = (page - 1) * messagesPerPage + i;
        const timestamp = new Date(baseDate.getTime() - i * 3600000); // One hour earlier per message

        olderMessages.push({
            id,
            text: `This is an older message #${id}. It was sent some time ago.`,
            sent: i % 2 === 0, // Alternate between sent and received
            timestamp,
        });
    }

    return olderMessages.reverse(); // Newest first
};

// Watch for changes in messages and scroll to bottom for new messages
watch(
    messages,
    (newVal, oldVal) => {
        // Only auto-scroll if the new message is at the end (sent by user or received)
        if (
            newVal.length > oldVal.length &&
            newVal[newVal.length - 1].id > oldVal[oldVal.length - 1].id
        ) {
            scrollToBottom();
        }
    },
    { deep: true }
);

// Initial setup
onMounted(() => {
    scrollToBottom();
});
</script>

<style scoped>
/* Custom scrollbar for the messages container */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 20px;
}

/* Loading spinner animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
