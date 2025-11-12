import { computed, onMounted, ref } from 'vue';

type Appearance = 'light' | 'dark';

const THEME = {
    DEFAULT_COLOR: 'dark' as Appearance,
    COOKIE_KEY: 'appearance',
    ONE_DAY: 24 * 60 * 60,
};

const isBrowser = () => typeof window !== 'undefined';
const isDocument = () => typeof document !== 'undefined';

export function updateTheme(value: Appearance) {
    if (!isBrowser()) return;
    document.documentElement.classList.toggle('dark', value === THEME.DEFAULT_COLOR);
}

const setCookie = (name: string, value: string, days = 365) => {
    if (!isDocument()) return;
    document.cookie = `${name}=${value};path=/;max-age=${days * THEME.ONE_DAY};SameSite=Lax`;
};


const getStoredAppearance = () => {
    if (!isBrowser()) return null;
    return localStorage.getItem(THEME.COOKIE_KEY) as Appearance | null;
};

export function initializeTheme() {
    if (!isBrowser()) return;

    const savedAppearance = getStoredAppearance();
    updateTheme(savedAppearance || THEME.DEFAULT_COLOR);
}

const appearance = ref<Appearance>(THEME.DEFAULT_COLOR);

export function useAppearance() {
    onMounted(() => {
        const savedAppearance = localStorage.getItem(THEME.COOKIE_KEY) as Appearance | null;

        if (savedAppearance) appearance.value = savedAppearance;
    });

    function updateAppearance(value: Appearance) {
        appearance.value = value;

        localStorage.setItem(THEME.COOKIE_KEY, value);

        setCookie(THEME.COOKIE_KEY, value);

        updateTheme(value);
    }

    const isDark = computed(() => appearance.value === 'dark');

    return {
        appearance,
        isDark,
        updateAppearance,
    };
}
