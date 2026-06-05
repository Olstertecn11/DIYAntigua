import FloatingActions from '@/Components/Public/FloatingActions';
import PublicFooter from '@/Components/Public/PublicFooter';
import PublicNavbar from '@/Components/Public/PublicNavbar';

export default function PublicLayout({ children }) {
    return (
        <div className="min-h-screen bg-white text-slate-950">
            <PublicNavbar />
            {children}
            <PublicFooter />
            <FloatingActions />
        </div>
    );
}
