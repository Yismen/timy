export const functions = {
    methods: {
        truncateElipse(str, n){    
            n = n < 4 ? 4 : n - 3
            return str.length > n ? `${str.substr(0, n).trim()}...` : str
        }
    }
}