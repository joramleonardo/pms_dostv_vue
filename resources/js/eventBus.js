import { reactive } from "vue";

const state = reactive({ events: {} });

export function useEventBus() {
    return {
        emit(event, ...args) {
            if (state.events[event]) {
                state.events[event].forEach(callback => callback(...args));
            }
        },
        on(event, callback) {
            if (!state.events[event]) {
                state.events[event] = [];
            }
            state.events[event].push(callback);
        },
        off(event, callback) {
            if (state.events[event]) {
                state.events[event] = state.events[event].filter(cb => cb !== callback);
            }
        }
    };
}
