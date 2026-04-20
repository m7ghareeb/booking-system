

export default function __(key: string, replacements: Record<string, string> = {}): string {
    const map = typeof window !== 'undefined' ? window.translations ?? {} : {};
    let translation = map[key] ?? map[`main.${key}`] ?? key;

    Object.keys(replacements).forEach((replacement) => {
        translation = translation.replace(`:${replacement}`, replacements[replacement]);
    });

    return translation;
}
