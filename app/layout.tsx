import type { Metadata } from "next";
import { Providers } from "@/components/ui/Providers";

export const metadata: Metadata = {
  title: "Antigua Transfers",
  description: "Reservas de traslados privados y compartidos",
};

export default function RootLayout({
  children,
}: Readonly<{ children: React.ReactNode }>) {
  return (
    // Añade suppressHydrationWarning aquí
    <html lang="es" suppressHydrationWarning>
      <body>
        <Providers>{children}</Providers>
      </body>
    </html>
  );
}
